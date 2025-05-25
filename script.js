// Set the current year dynamically
document.getElementById('year').textContent = `${new Date().getFullYear()}`;

$(document).ready(function() {
    // Fetch CSRF token on page load
    $.ajax({
        url: 'get-csrf.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.csrf_token) {
                $('#csrf_token').val(response.csrf_token);
            } else {
                $('#form-message').text('Failed to load CSRF token. Please refresh the page.').css('color', 'red');
            }
        },
        error: function() {
            $('#form-message').text('Failed to load CSRF token. Please refresh the page.').css('color', 'red');
        }
    });

    $('#contact-form').on('submit', function(event) {
        event.preventDefault();

        var form = $(this);
        var formData = form.serialize();
        var messageDiv = $('#form-message');
        var submitBtn = $('#submit-btn');

        // Disable button and show loading state
        submitBtn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.message && response.message.toLowerCase().includes('error')) {
                    messageDiv.text(response.message).css('color', 'red');
                } else {
                    messageDiv.text(response.message).css('color', 'green');
                    form[0].reset();
                }
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'An error occurred. Please try again.';
                messageDiv.text(errorMessage).css('color', 'red');
            },
            complete: function() {
                // Re-enable button after request completes
                submitBtn.prop('disabled', false).text('Send Message');
            }
        });
    });
});