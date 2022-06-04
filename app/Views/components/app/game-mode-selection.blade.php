<div class="form-group table-filter">
    <div class="input table-filter">
        <label for="{{ $id }}">Game Mode</label>
        <select name="{{ $name }}" id="{{ $id }}">
            <option value="{{ \App\Models\GameMode::ALL_ID }}" selected>All</option>
            @foreach($modes as $mode)
                <option value="{{ $mode->id }}">{{ $mode->title }}</option>
            @endforeach
        </select>
    </div>
</div>