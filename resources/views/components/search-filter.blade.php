{{-- Reusable Search Filter Component --}}
@props([
    'route' => '',
    'title' => 'Search & Filter',
    'icon' => 'bi-funnel-fill',
    'fields' => [],
    'advanced' => false
])

<div class="card mb-4">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="{{ $icon }}"></i> {{ $title }}
            </h5>
            @if($advanced)
                <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleAdvanced">
                    <i class="bi bi-gear"></i> Advanced
                </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET" id="searchForm">
            <div class="row g-3">
                @foreach($fields as $field)
                    <div class="col-md-{{ $field['col'] ?? 4 }}">
                        <label class="form-label small">{{ $field['label'] }}</label>
                        
                        @if($field['type'] === 'text')
                            <input type="text" 
                                   class="form-control" 
                                   name="{{ $field['name'] }}" 
                                   placeholder="{{ $field['placeholder'] ?? '' }}" 
                                   value="{{ request($field['name']) }}">
                        
                        @elseif($field['type'] === 'select')
                            <select class="form-select" name="{{ $field['name'] }}">
                                <option value="">{{ $field['placeholder'] ?? 'All' }}</option>
                                @foreach($field['options'] as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ request($field['name']) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        
                        @elseif($field['type'] === 'date')
                            <input type="date" 
                                   class="form-control" 
                                   name="{{ $field['name'] }}" 
                                   value="{{ request($field['name']) }}">
                        
                        @elseif($field['type'] === 'number')
                            <input type="number" 
                                   class="form-control" 
                                   name="{{ $field['name'] }}" 
                                   placeholder="{{ $field['placeholder'] ?? '' }}" 
                                   value="{{ request($field['name']) }}"
                                   min="{{ $field['min'] ?? '' }}"
                                   max="{{ $field['max'] ?? '' }}">
                        @endif
                    </div>
                @endforeach
                
                {{-- Action Buttons --}}
                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <a href="{{ $route }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                        @if(isset($exportRoute))
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-download"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ $exportRoute }}?{{ http_build_query(request()->all()) }}">
                                        <i class="bi bi-file-excel"></i> Export to Excel
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ $exportRoute }}?format=pdf&{{ http_build_query(request()->all()) }}">
                                        <i class="bi bi-file-pdf"></i> Export to PDF
                                    </a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Advanced Filters (Hidden by default) --}}
            @if($advanced)
                <div id="advancedFilters" class="mt-3" style="display: none;">
                    <hr>
                    <div class="row g-3">
                        {{ $advancedSlot ?? '' }}
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle advanced filters
    const toggleBtn = document.getElementById('toggleAdvanced');
    const advancedFilters = document.getElementById('advancedFilters');
    
    if (toggleBtn && advancedFilters) {
        toggleBtn.addEventListener('click', function() {
            if (advancedFilters.style.display === 'none') {
                advancedFilters.style.display = 'block';
                this.innerHTML = '<i class="bi bi-gear-fill"></i> Hide Advanced';
            } else {
                advancedFilters.style.display = 'none';
                this.innerHTML = '<i class="bi bi-gear"></i> Advanced';
            }
        });
    }
    
    // Auto-submit on select change (optional)
    const autoSubmitSelects = document.querySelectorAll('.auto-submit');
    autoSubmitSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    });
});
</script>
@endpush
