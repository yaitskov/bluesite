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


function hookCommentHandlers(root) {
    root.on('click','.time a.delete',
            function (e) {
                e.preventDefault();
	        var th=$(this),
		container=th.closest('div.comment'),
		id=container.attr('id').slice(1);
                confirmOperation(
                    'Are you sure you want to delete comment #'+id+'?',
                    function () {
                        $.ajax({ url:th.attr('href') }).done(
                            function (data) {
                                if (data.error) {
                                    showErrorMessage(data.error);
                                } else {
                                    container.slideUp();                                
                                }
                            });
                    });
            });
}




