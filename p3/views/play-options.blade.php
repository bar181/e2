<div class="col-6 text-center">

    <hr>
    <div class=" my-4 card">
        <form method="POST" action="post_play">
            <div class="my-4 d-flex justify-content-around fs-3">
                <div class="form-check px-3">
                    <input test="player-stand" class="form-check-input" type="radio" name="hitstand" id="stand"
                        value="stand" {{ $round['player']['score'] > 15 ? 'checked' : '' }}>
                    <label class="form-check-label" for="stand">
                        Stand
                    </label>
                </div>
                <div class="form-check px-3">
                    <input test="player-hit" class="form-check-input" type="radio" name="hitstand" id="hit"
                        value="hit" {{ $round['player']['score'] <= 15 ? 'checked' : '' }}>
                    <label class="form-check-label" for="hit">
                        Hit
                    </label>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <input type="hidden" name="round_id" id="round_id" value="{{ $player['round_id'] }} ">
                <button test="hitstand-submit" type="submit" class="btn btn-primary mb-3 fs-1">Play !</button>
            </div>
        </form>
    </div>



</div>
