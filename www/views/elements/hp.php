<hr>
<h1>You can now search something</h1>
<div class="grid-x grid-padding-x">

    <div class="cell medium-6">
        <div class="callout primary">
            <h2>Search with elasticsearch</h2>
            <p>Some elastic magic, words are added to index for them to be searchable.</p>
            <form action="/elasticSearch">
                <div class="input-group">
                    <input class="input-group-field" name="q" minlength="3">
                    <div class="input-group-button">
                        <input type="submit" class="button" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="cell medium-6">
        <div class="callout secondary">
            <h2>Search with levenshtein</h2>
            <p>Iterates throught whole database, slow for many results and wont show partial match.</p>
            <form action="/levenshteinSearch">
                <div class="input-group">
                    <input class="input-group-field" name="q" minlength="3">
                    <div class="input-group-button">
                        <input type="submit" class="button" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="column medium-6"></div>
</div>

