<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");

    .star-widget input {
        display: none;
    }

    .star-widget label {
        font-size: 35px;
        color: #444;
        padding: 10px;
        float: right;
        transition: all 0.2s ease;
    }

    input:not(:checked)~label:hover,
    input:not(:checked)~label:hover~label {
        color: #fd4;
    }

    input:checked~label {
        color: #fd4;
    }

    #rate-1:checked~.rating-desc:before {
        content: "Poor😋";
    }

    #rate-2:checked~.rating-desc:before {
        content: "Not bad";
    }

    #rate-3:checked~.rating-desc:before {
        content: "Average";
    }

    #rate-4:checked~.rating-desc:before {
        content: "Good😋";
    }

    #rate-5:checked~.rating-desc:before {
        content: "Excellent😋";
    }

    .rating-desc {
        width: 100%;
        font-size: 20px;
        font-weight: 600;
        margin: 5px 0 20px 0;
        text-align: center;
        transition: all 0.2s ease;
    }

    .textarea textarea {
        border: 1px solid #e4e5e7;
        background: white;
        color: #6c6c6e;
        padding: 22px;
        font-size: 16px;
        margin-top: 15px;
        letter-spacing: -0.011em;
        border-radius: 10px;
        resize: none;
    }

    .textarea textarea:focus {
        border-color: #36bb91 !important;
        background: white;
        color: #1a1a1a;
        outline: none;
    }

    .btn {
        height: 45px;
        width: 100%;
        margin: 15px 0;
    }

    .btn:active {
        border: none;
    }

    :not(.btn-check)+.btn:active {
        outline: none;
    }

    .btn button {
        height: 100%;
        width: 60%;
        outline: none;
        background: #36bb91;
        color: #fff;
        font-size: 17px;
        font-weight: 600;
        border-radius: 15px;
        cursor: pointer;
        border: none;
    }

    .btn button:hover {
        background: #1a5e49;
    }

    .star-rating-bx {
        background-color: #fff;
        box-shadow: 0px 4px 40px 0px rgb(0 0 0 / 5%);
        border-radius: 10px;
        padding: 40px;
    }

    @media (max-width: 576px) {
        .star-rating-bx {
            padding: 20px 15px;
        }
    }

    .star-icon {
        padding-bottom: 20px;
    }

    .modal-title {
        font-weight: 600;
    }

    #error-comment,
    #error-rating {
        color: red;
    }
</style>
<section class="section" id="formContainer" style="display:none;">
    <div class="star-rating-bx">
        <h2 class="text-center">Feedback</h2>
        <div class="star-widget">
            <form name="feedback" onsubmit="feedBack();">
                <input type="radio" name="star" id="rate-5" value="5">
                <label for="rate-5" class="fa fa-solid fa-star"></label>
                <input type="radio" name="star" id="rate-4" value="4">
                <label for="rate-4" class="fa fa-solid fa-star"></label>
                <input type="radio" name="star" id="rate-3" value="3">
                <label for="rate-3" class="fa fa-solid fa-star"></label>
                <input type="radio" name="star" id="rate-2" value="2">
                <label for="rate-2" class="fa fa-solid fa-star"></label>
                <input type="radio" name="star" id="rate-1" value="1">
                <label for="rate-1" class="fa fa-solid fa-star"></label>
                <p id="error-rating"></p>
                <p class="rating-desc"></p>
                <div class="textarea">
                    <textarea cols="30" id="comment" name="comment" placeholder="Describe your comment"></textarea>
                    <p id="error-comment"></p>
                </div>
                <div class="btn">
                    <button type="submit">Submit</button>
                </div>
                <div class="modal fade" id="simpleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="exampleModalLabel">Thank you</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    onClick="reload"></button>
                            </div>
                            <div class="modal-body">
                                <p>We appreciate you sending us your feedback.</p>
                                <p><strong>You're select </strong>⭐ <span id="star_count"></span></p>
                                <p><strong>Your comment </strong><span id="comment_text"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    function feedBack() {
        var comment = document.getElementById('comment').value;
        var rating = $("input[name=star]:checked").val();

        // Validate comment
        if (comment === '') {
            document.getElementById('error-comment').innerHTML = "* Please enter comment";
            return false; // Stop form submission
        } else {
            document.getElementById('error-comment').innerHTML = "";
        }

        // Validate rating
        if (!rating) {
            document.getElementById('error-rating').innerHTML = "* Please choose a star rating";
            return false; // Stop form submission
        } else {
            document.getElementById('error-rating').innerHTML = "";
        }

        // If all validations pass, proceed with AJAX submission
        $.ajax({
            url: '/reviewSend', // Replace with your actual URL for form submission
            type: 'POST',
            data: {
                rating: rating,
                comment: comment
            },
            success: function(response) {
                // Show success modal or handle response as needed
                // $('#simpleModal').modal('show'); // Assuming you're using Bootstrap modal
                // document.getElementById('star_count').innerHTML = rating;
                // document.getElementById('comment_text').innerHTML = comment;
                alert('success');
            },
            error: function(error) {
                console.error('Error:', error);
                alert('error', error);

            }
        });

        return false; // Prevent default form submission
    }
</script>
