<style>
    .form-inline{margin-bottom: 10px}
    #error-msg{color: red}
    #cache-del{display: inline; margin-bottom: 10px}
</style>

<div class="page-header"><h1>Факторизация целого числа</h1></div>
<form class="form-inline" method="post" id="factorization-form">
    <div class="form-group">
        <label class="sr-only" for="inputEmail">Число </label>
        <input type="text" class="form-control" id="num" name="num" placeholder="Введите число">
    </div>
    <button type="submit" class="btn btn-primary">Разложить</button>
</form>
<button type="submit" class="btn btn-primary" id="cache-del">Сбросить кэш</button>
<div id="error-msg"></div>
<div id="data-output"></div>

<script>
    (function($){

        var $num = $('#num');
        var $dataOutput = $('#data-output');
        var $errorMsg = $('#error-msg');
        var $disabled = $('#num, .btn')

        $num.keypress(function (e) {
            var theEvent = e || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var regex = /[0-9]/;
            if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        });

        $('#factorization-form').submit(function (e) {
            e.preventDefault();
            var num = $num.val();
            if (num) {
                $disabled.prop('disabled', true);
                $.ajax({
                    url: '/factorization/' + num,
                    type: 'GET',
                    dataType: 'json',
                    success: function (ans) {
                        var data = '';
                        if (ans.data) {
                            data = num + ' = ' + ans.data.join(' &times ');
                        }
                        $dataOutput.html(data);
                        $errorMsg.text(ans.error);
                        $disabled.prop('disabled', false);
                    }
                }).fail(function () {
                    $disabled.prop('disabled', false);
                });
            }
        });

        $('#cache-del').click(function () {
            $.ajax({
                url: '/factorization/delete-cache',
                type: 'GET',
                dataType: 'json',
                success: function (ans) {
                    $dataOutput.html(ans.data);
                    $errorMsg.text(ans.error);
                }
            });
        });
    })(jQuery);
</script>
