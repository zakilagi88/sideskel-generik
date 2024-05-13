<div>
    <div class="mb-6">
        <x-web.breadcrumbs :items="[$this->getPageBreadcrumb()]" :heading="$this->getPageHeading()" />
    </div>

    <div>
        {{ $this->table }}
    </div>
</div>
