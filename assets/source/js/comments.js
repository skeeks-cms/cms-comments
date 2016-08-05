/*!
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 26.06.2016
 */
(function(sx, $, _)
{
    sx.classes.SkeekSComments = sx.classes.Component.extend({

        _onDomReady: function()
        {
             var self = this;

             $(document).on('click', '.comment .reply-button', function (event) {
                event.preventDefault();
                var currentForm = $(this).closest('.comment').find('.reply-form');

                $.post(self.get('commentsFormLink'), {reply_to: $(this).attr('data-reply-to')})
                    .done(function (data) {
                        $('.comments .reply-form').not($(currentForm)).hide(self.get('displayFormDuration', 500));
                        $(this).closest('.comment').find('> .reply-form').show(self.get('displayFormDuration', 500));
                        $(currentForm).hide().html(data).show(self.get('displayFormDuration', 500));
                    });
             });


            //Show 'username' and 'email' fields in main form and hide all reply forms
            $(document).on('click', '.comments-main-form .field-cmscomment-content', function (event) {
                event.preventDefault();
                $('.comments-main-form').find('.comment-fields-more').show(self.get('displayFormDuration', 500));
                $('.reply-form').hide(self.get('displayFormDuration', 500));
            });

            //Hide reply form on 'Cancel' click
            $(document).on('click', '.reply-cancel', function () {
                $(this).closest('.reply-form').hide(self.get('displayFormDuration', 500));
            });

            //Disable submit button after click
            $(document).on('beforeValidate', ".comments-main-form form, .comment-form form", function (event, messages) {
                $(this).find("[type=submit]").prop('disabled', true);
            });

            //Enable submit button if form has errors
            $(document).on('afterValidate', ".comments-main-form form, .comment-form form", function (event, messages) {
                var hasError = false;

                for (var propertyName in messages) {
                    hasError = hasError || !(!messages[propertyName] || 0 === messages[propertyName].length);
                }

                if (hasError) {
                    $(this).find("[type=submit]").prop('disabled', false);
                }
            });
        },

    });
})(sx, sx.$, sx._);

