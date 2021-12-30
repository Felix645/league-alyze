<div id="add-match-modal" class="modal-background">
    <div class="modal">
        <button class="modal-close" data-target="#add-match-modal">
            <i class="fas fa-times"></i>
        </button>

        <div class="header">
            <h3>Add Match</h3>
        </div>

        <div class="body">
            <form action="{{ route('matches.create')->full() }}" method="post">
                @csrf

                <div class="form-group">
                    <div class="input">
                        <label for="is_win">Was your match a win?</label>
                        <input type="checkbox" name="is_win" id="is_win" value="1">
                    </div>

                    <div class="input">
                        <label for="role_id">Select your role</label>
                        <select name="role_id" id="role_id">
                            <option value="" selected>Please select ...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input">
                        <label for="played_as">Champion you played as</label>
                        <select name="played_as" id="played_as">
                            <option value="" selected>Please select ...</option>
                            @foreach($champions as $champion)
                                <option value="{{ $champion->id }}">{{ $champion->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input">
                        <label for="played_against">Champion you played against</label>
                        <select name="played_against" id="played_against">
                            <option value="" selected>Please select ...</option>
                            @foreach($champions as $champion)
                                <option value="{{ $champion->id }}">{{ $champion->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input">
                        <label for="kills">Enter your kills</label>
                        <input type="text" name="kills" id="kills">
                    </div>

                    <div class="input">
                        <label for="deaths">Enter your deaths</label>
                        <input type="text" name="deaths" id="deaths">
                    </div>

                    <div class="input">
                        <label for="assists">Enter your assists</label>
                        <input type="text" name="assists" id="assists">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input">
                        <label for="creep_score">Enter your creep score</label>
                        <input type="text" name="creep_score" id="creep_score">
                    </div>
                </div>

                <div class="form-group">
                    <div class="input">
                        <label for="minutes">Game length in minutes</label>
                        <input type="text" name="minutes" id="minutes">
                    </div>

                    <div class="input">
                        <label for="seconds">Remaining Game length in seconds</label>
                        <input type="text" name="seconds" id="seconds">
                    </div>
                </div>

                <div class="form-group center">
                    <button type="submit" class="button">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>