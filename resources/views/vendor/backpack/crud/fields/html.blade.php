<style>
    .rating {
        display: inline-block;
        position: relative;
        height: 50px;
        line-height: 50px;
        font-size: 20px;
        margin-left: 2em;
        margin-top: -0.5em;
    }

    .rating label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        cursor: pointer;
    }

    .rating label:last-child {
        position: static;
    }

    .rating label:nth-child(1) {
        z-index: 5;
    }

    .rating label:nth-child(2) {
        z-index: 4;
    }

    .rating label:nth-child(3) {
        z-index: 3;
    }

    .rating label:nth-child(4) {
        z-index: 2;
    }

    .rating label:nth-child(5) {
        z-index: 1;
    }

    .rating label input {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
    }

    .rating label .icon {
        float: left;
        color: transparent;
    }

    .rating label:last-child .icon {
        color: #000;
    }

    .rating:not(:hover) label input:checked~.icon,
    .rating:hover label:hover input~.icon {
        color: #09f;
    }

    .rating label input:focus:not(:checked)~.icon:last-child {
        color: #000;
        text-shadow: 0 0 5px #09f;
    }
</style>
<label style="margin-left: 1em;"> Rating: </label>
{{-- {{ dd($field['value']) }} --}}
<div class="rating">
    <label>
        <input type="radio" name="rating" value="1" @if ($field['value'] == 1)checked @endif />
        <span class="icon">★</span>
    </label>
    <label>
        <input type="radio" name="rating" value="2" @if ($field['value'] == 2)checked @endif />
        <span class="icon">★</span>
        <span class="icon">★</span>
    </label>
    <label>
        <input type="radio" name="rating" value="3"  @if ($field['value'] == 3)checked @endif/>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
    </label>
    <label>
        <input type="radio" name="rating" value="4"  @if ($field['value'] == 4)checked @endif/>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
    </label>
    <label>
        <input type="radio" name="rating" value="5"  @if ($field['value'] == 5) checked @endif/>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
    </label>
</div>
{{-- <input type="hidden" name="rate" id="rate" value=""> --}}

