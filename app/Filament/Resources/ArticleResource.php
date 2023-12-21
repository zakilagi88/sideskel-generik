<?php

namespace App\Filament\Resources;

use App\Enum\Website\Status_Post;
use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'fas-newspaper';
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Berita';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns([
                        'sm' => 1,
                        'lg' => 3,
                    ])
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()

                                            ->label('Judul')
                                            ->live(onBlur: true)

                                            ->afterStateUpdated(
                                                function (string $operation, $state, Set $set) {
                                                    if ($operation === 'create') {
                                                        $set('title', Str::title($state));
                                                        $set('slug', Str::slug($state));
                                                    }
                                                }
                                            ),

                                        Forms\Components\TextInput::make('slug')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required()
                                            ->unique(Article::class, 'slug', ignoreRecord: true),

                                        Forms\Components\MarkdownEditor::make('body')
                                            ->required()
                                            ->label('Isi Artikel')
                                            ->columnSpanFull(),

                                        Forms\Components\Select::make('user_id')
                                            ->relationship('user', 'name')
                                            ->label('Penulis')
                                            ->preload()
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->label('Kategori')
                                            ->searchable()
                                            ->preload()
                                            ->required(),


                                        Forms\Components\SpatieTagsInput::make('tags')
                                            ->label('Meta Tags')
                                            ->type('tags-article')
                                            ->hint('Meta Tags untuk SEO')
                                            ->hintColor('info')
                                            ->hintIcon('heroicon-o-information-circle', tooltip: 'Meta Tags untuk SEO')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Image')
                                    ->schema([
                                        Forms\Components\FileUpload::make('featured_image_url')
                                            ->label('Image')
                                            ->hiddenLabel()
                                            ->disk('public')
                                            ->directory('articles')
                                            ->image()
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->imagePreviewHeight('250')
                                            ->loadingIndicatorPosition('right')
                                            ->panelAspectRatio('2:1')
                                            ->panelLayout('integrated')
                                            ->removeUploadedFileButtonPosition('right')
                                            ->uploadButtonPosition('left')
                                            ->uploadProgressIndicatorPosition('left'),
                                        Textarea::make('meta_description')
                                            ->label('Deskripsi')
                                            ->hint('Deskripsi artikel untuk SEO')
                                            ->hintColor('info')
                                            ->hintIcon('heroicon-o-information-circle', tooltip: 'Deskripsi artikel untuk SEO')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options(Status_Post::class)
                                            ->hintColor('info')
                                            ->hintIcon('heroicon-o-information-circle', tooltip: 'Status Artikel')
                                            ->required(),

                                        Forms\Components\DateTimePicker::make('scheduled_for')
                                            ->label('Ditampilkan pada')
                                            ->required(),

                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Dipublikasikan pada')
                                            ->required(),
                                    ])
                                    ->columnSpan(['lg' => 1]),
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('created_at')
                                            ->label('Dibuat pada')
                                            ->content(fn (Article $record): ?string => $record->created_at?->diffForHumans()),

                                        Forms\Components\Placeholder::make('updated_at')
                                            ->label('Diperbarui pada')
                                            ->content(fn (Article $record): ?string => $record->updated_at?->diffForHumans()),
                                    ])
                                    ->columnSpan(['lg' => 1])
                                    ->hidden(fn (?Article $record) => $record === null),
                            ])

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\ImageColumn::make('featured_image_url')
                    ->label('Gambar')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->description(
                        fn (Article $record) => $record->meta_description
                    )
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penulis')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),


                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->icon('fas-folder')
                    ->badge()
                    ->colors([
                        'primary' => 'blue',
                        'success' => 'green',
                        'warning' => 'yellow',
                        'danger' => 'red',
                        'info' => 'indigo',
                        'gray' => 'gray',
                    ])
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('scheduled_for')
                    ->label('Dijadwalkan pada')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default(''),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasikan pada')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('comments.customer.name')
                //     ->label('Comment Authors')
                //     ->listWithLineBreaks()
                //     ->limitList(2),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Status_Post::class)
                    ->preload(),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Kategori')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('tags')
                    ->multiple()
                    ->preload()
                    ->options(Tag::getWithType('tags-article')->pluck('name', 'name'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['values'], function (Builder $query, $data): Builder {
                            return $query->withAnyTags(array_values($data), 'tags-article');
                        });
                    }),

                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')
                            ->label('Dipublikasikan dari')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('published_until')
                            ->label('Dipublikasikan sampai')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['published_from'] ?? null) {
                            $indicators['published_from'] = 'Published from ' . Carbon::parse($data['published_from'])->toFormattedDateString();
                        }
                        if ($data['published_until'] ?? null) {
                            $indicators['published_until'] = 'Published until ' . Carbon::parse($data['published_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user', 'category']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'user.name', 'category.name', 'tags.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Article $record */
        $details = [];


        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        if ($record->category) {
            $details['Category'] = $record->category->name;
        }

        if ($record->tags) {
            $details['Tags'] = $record->tags->pluck('name')->join(', ');
        }

        return $details;
    }
}