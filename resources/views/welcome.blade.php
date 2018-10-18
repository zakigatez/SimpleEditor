<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <script src="/codemirror//lib/codemirror.js"></script>
    <link rel="stylesheet" type="text/css" href="/codemirror//lib/codemirror.css">
    <script src="/codemirror//mode/javascript/javascript.js"></script>
    <script src="/codemirror//mode/xml/xml.js"></script>
    <script src="/codemirror//addon/edit/closetag.js"></script>
    <link rel="stylesheet" type="text/css" href="/codemirror//theme/dracula.css">

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Document</title>
</head>
<body class="full-height bg-secondary">


            <div class="card bg-dark mb-md-3">
                <div class="card-header col-md-auto m-2">
                        <div class="btn-group btn-group-justified col-md-8">
                            <button id="run" class="btn btn-success col-md-2 ">run</button>
                            <button id="save" class="btn btn-primary col-md-2 mr-auto">save</button>
                        </div>
                </div>
                <div class="errors alert alert-danger" style="display: none;"></div>
                <div class="successed alert alert-success" style="display: none;"></div>
                <div class="card-body form-inline">
                    <div class="col-md-6 mx-auto my-1 input">
                        <label class="btn btn-light disabled" for="html"> HTML Code</label>
                        <textarea class="textarea col-md-12 mx-auto" name="html" id="html" rows="10"></textarea>
                    </div>

                    <div class="col-md-6 mx-auto my-1">
                        <label class="btn btn-light disabled" for="javascript"> JAVASCRIPT Code</label>
                        <textarea class="textarea col-md-12 mx-auto" name="javascript" id="javascript"  rows="10"></textarea>
                    </div>
                </div>
            </div>
        <div class="card p-0 mx-auto col-md-11 mb-md-3">
                <div class="card-header">
                    <h1 class="btn btn-light disabled mr-auto h1" > Page view</h1>
                </div>

                <div class="card-body" id="body" style="min-height: 325px">


                </div>
        </div>
    <script type="text/javascript">

        var javascript = CodeMirror.fromTextArea(document.getElementById("javascript"),{
            mode:"javascript",
            theme:"dracula",
            lineNumbers: true,
            autoCloseTags:true
        })
        var html = CodeMirror.fromTextArea(document.getElementById("html"),{
            mode:"xml",
            theme:"dracula",
            lineNumbers: true,
            autoCloseTags:true
        })

        $('#run').click(function ()
        {
            $('#body').children().remove();

            $("#addedscript").children().remove();
            $('#body').append($.parseHTML( html.getValue()));
            $("#addedscript").append(javascript.getValue());

        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#save").click(function(e) {
            e.preventDefault();
            $.ajax({
            type: 'POST',
            url: "/theme/create",
            data: {
                    body: html.getValue(),
                    script: javascript.getValue(),
                    }
                ,
                success: function (data) {

                    if (data.errors){
                        $.each(data.errors, function(key, value){
                            $('.errors').append("<li>"+value+"</li>");
                        })
                        $('.errors').show()
                    }
                    else{
                        $('.successed').append(data.success);
                        $('.successed').show()
                    }
                },
            });
        });
        $(document).click(function () {
            $('.alert').children().remove()
            $('.alert').hide()
            }
        )
    </script>

        <div id="addedscript">

        </div>

</body>
</html>