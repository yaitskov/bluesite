/**
 *  common code used in many places
 */

function showErrorMessage(msg) {
    var msgDiv = $("<div class='errmsg'></div>");
    msgDiv.text(msg);
    msgDiv.click(function () {
                     $(this).remove();
                 });
    $('#errbox').append(msgDiv);
}

function confirmOperation(question, callback) {
    var qbox = $('.templates .questionbox').clone();
    qbox.find('.questiontext').text(question);
    $('#errbox').append(qbox);
    qbox.find('#btnyes').click(
        function (e) {
            e.preventDefault();
            qbox.remove();
            callback();
        }
    );
    qbox.find('#btnno').click(
        function (e) {
            e.preventDefault();
            qbox.remove();
        }
    );
}

function fixCommentHeaderMore() {
    var l = $('#comments .comment:visible').length;
    if (l == 0) {
        $('#nocomments').hide();
        $('#onecomment').show();
    } else if (l == 1) {
        $('#onecomment').hide();
        $('#ncomments').show();
    }
    $('#ncomments .numx').text(1 + parseInt($('#ncomments .numx').text()));    
}

function fixCommentHeaderLow() {
    var l = $('#comments .comment:visible').length;
    if (l == 1) {
        $('#nocomments').show();
        $('#onecomment').hide();
    } else if (l == 2) {
        $('#onecomment').show();
        $('#ncomments').hide();
    }    
    $('#ncomments .numx').text(parseInt($('#ncomments .numx').text()) - 1);
}

function hookCommentHandlers(root) {
    var newDels = root.find('a.delete');
    newDels.click(
        function (e) {
            e.preventDefault();
	    var th = $(this),
	    container = th.closest('div.comment'),
	    id=container.attr('id').slice(1);
            confirmOperation(
                'Are you sure you want to delete comment #'+id+'?',
                function () {
                    $.ajax({ url:th.attr('href') }).done(
                        function (data) {
                            if (data.error) {
                                showErrorMessage(data.error);
                            } else {
                                fixCommentHeaderLow();
                                container.slideUp();                                
                            }
                        });
                });
        });
}

function uploadFileDialog(requestUrl) {
    $.ajax({ url: requestUrl }).done(
        function (data) {
            if (data.error) {
                showErrorMessage(data.error);
            } else { // got form
                var form = $(data.body);
                form.submit(
                    function (e) {                        
                        return false;
                    });
                form.find('.upload-button').click(
                    function () {
                        form.submit();
                    }
                );
                form.find('.cancel-upload').click(
                    function () {
                        form.remove();                        
                    }
                );
                $('#errbox').append(form);                
            }
        });    
}




