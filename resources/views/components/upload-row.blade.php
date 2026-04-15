    <div data-upload-row>
    @foreach ([
        ['type' => 'photo', 'icon' => '👤', 'label' => 'Passport Photo', 'hint' => 'Portrait / face photo'],
        ['type' => 'front', 'icon' => '📄', 'label' => 'Passport Front', 'hint' => 'Bio data page'],
        ['type' => 'back',  'icon' => '📋', 'label' => 'Passport Back',  'hint' => 'Last / signature page'],
    ] as $doc)
        <template data-upload-template
            data-type="{{ $doc['type'] }}"
            data-icon="{{ $doc['icon'] }}"
            data-label="{{ $doc['label'] }}"
            data-hint="{{ $doc['hint'] }}">
        </template>
    @endforeach
</div>