<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

abstract class ImportSettings implements ToCollection

{
    /**
     * Set additional user resolve callback.
     *
     * @var \Illuminate\Support\Collection
     */
    protected static $additionalUsersCallback;


    abstract public function processImport(Collection $rows);

    abstract public function rules(): array;

    abstract public function getUser();

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        if ($this instanceof WithValidation) {
            $rows = $this->validate($rows);
        }

        try {
            $this->processImport($rows);
        } catch (Throwable $e) {
            $this->recordOrThrowErrors($e);
        }

        if ($this->failures()->count() > 0) {
            $path = "excels/userFailures/import_failures_" . now()->format('Y-m-d H:i:s') . '.xlsx';
            (new UsersFailuresImportedExport($this->failures()))->store($path);
        }

        if ($this->errors()->count() > 0) {
            Log::error($this->errors());
        }
    }

    /**
     * Validate given collection data.
     *
     * @param Collection $rows
     *
     * @throws ValidationException
     *
     * @return Collection
     */
    protected function validate(Collection $rows)
    {
        $validator = Validator::make($rows->toArray(), $this->rules());

        if (!$validator->fails()) {
            return $rows;
        }

        if ($this instanceof SkipsOnFailure) {
            $this->onFailure(
                ...$this->collectErrors($validator, $rows)
            );

            $keysCausingFailure = collect($validator->errors()->keys())->map(function ($key) {
                return Str::before($key, '.');
            })
                ->values()
                ->toArray();

            return $rows->except($keysCausingFailure);
        }

        throw new ValidationException($validator);
    }

    /**
     * Get all validation errors.
     *
     * @param $validator
     * @param Collection $rows
     *
     * @return array
     */
    protected function collectErrors($validator, Collection $rows): array
    {
        $failures = [];

        foreach ($validator->errors() as $attribute => $messages) {
            $row = strtok($attribute, '.');
            $attributeName = strtok('');
            $attributeName = $attributes['*.' . $attributeName] ?? $attributeName;

            $failures[] = new Failure(
                $row,
                $attributeName,
                str_replace($attribute, $attributeName, $messages),
                $rows[$row] ?? []
            );
        }


        return $failures;
    }

    /**
     * Records an error or throws its exception.
     *
     * @param Throwable $error
     *
     * @throws \Exception
     */
    protected function recordOrThrowErrors(Throwable $error)
    {
        if ($this instanceof SkipsOnError) {
            return $this->onError($error);
        }

        throw $error;
    }

    /**
     * Set additional users callback.
     *
     * @param Callable $callback
     *
     * @return self
     */
    public static function additionalUsers(callable $callback): ImportSettings
    {
        static::$additionalUsersCallback = $callback;
    }
}