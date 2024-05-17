@php
    $relationManagers = $this->getRelationManagers() ?? [];
    $hasCombinedRelationManagerTabsWithContent = $this->hasCombinedRelationManagerTabsWithContent() ?? false;
@endphp

<div class="space-y-6">
    <div class="mb-6">
        <x-web.breadcrumbs :items="[$this->getPageBreadcrumb(), $this->getShiftPageBreadcrumb()]" :heading="$this->getPageHeading()" />
    </div>

    <div>
        {{ $this->infolist }}
    </div>

    <div>
        @if (!$hasCombinedRelationManagerTabsWithContent || !count($relationManagers))
            {{ $this->form }}
        @endif

        @if (count($relationManagers))
            <x-filament-panels::resources.relation-managers :active-locale="isset($activeLocale) ? $activeLocale : null" :active-manager="$this->activeRelationManager ??
                ($hasCombinedRelationManagerTabsWithContent ? null : array_key_first($relationManagers))" :content-tab-label="$this->getContentTabLabel()"
                :content-tab-icon="$this->getContentTabIcon()" :content-tab-position="$this->getContentTabPosition()" :managers="$relationManagers" :owner-record="$record" :page-class="static::class">
                @if ($hasCombinedRelationManagerTabsWithContent)
                    <x-slot name="content">
                        {{ $this->form }}
                    </x-slot>
                @endif
            </x-filament-panels::resources.relation-managers>
        @endif
    </div>

</div>
