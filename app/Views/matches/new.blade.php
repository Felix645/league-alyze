<h3>Add Match</h3>

<form action="{{ route('matches.create')->full() }}" method="post">
    @csrf

    <label for="is_win">Win?</label>
    <select name="is_win" id="is_win">
        <option value="1">Win</option>
        <option value="0">Loss</option>
    </select>

    <br><br>

    <label for="role_id">Role</label>
    <select name="role_id" id="role_id">
        @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
    </select>

    <br><br>

    <label for="played_as">Played as</label>
    <input list="champions" type="text" name="played_as" id="played_as">

    <label for="played_against">Played against</label>
    <input list="champions" type="text" name="played_against" id="played_against">

    <datalist id="champions">
        @foreach($champions as $champion)
            <option value="{{ $champion->name }}"></option>
        @endforeach
    </datalist>

    <br><br>

    <label for="kills">Kills</label>
    <input type="text" name="kills" id="kills">

    <label for="deaths">Deaths</label>
    <input type="text" name="deaths" id="deaths">

    <label for="assists">Assists</label>
    <input type="text" name="assists" id="assists">

    <br><br>

    <label for="creep_score">Creep Score</label>
    <input type="text" name="creep_score" id="creep_score">

    <br><br>

    <label for="minutes">Minutes</label>
    <input type="text" name="minutes" id="minutes">

    <label for="seconds">Seconds</label>
    <input type="text" name="seconds" id="seconds">

    <br><br>

    <button type="submit">Submit</button>
</form>