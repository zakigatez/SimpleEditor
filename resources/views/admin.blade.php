<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" integrity="sha384-pjaaA8dDz/5BgdFUPX6M/9SUZv4d12SUPF0axWc+VRZkx5xU3daN+lYb49+Ax+Tl" crossorigin="anonymous"></script>

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

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
                            <button id="save" class="btn btn-primary col-md-2 ml-auto">save</button>
                            <button id="run" class="btn btn-success col-md-2 ">run</button>
                            <button id="preview" class="btn btn-info col-md-2 ">preview</button>
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

        <div class="row m-2 mx-auto bg-secondary"style="min-height: 400px">



            {{--Page Editor--}}

            <div class="pages card  p-0 col-md-3 mb-3 bg-secondary">
                <div class="card-header bg-light">
                    <button class="btn btn-light disabled h1" > Configuration Pages
                    </button>
                    <button class="btn btn-success float-right"  data-toggle="modal" data-target="#createPage">
                        <i class="fa fa-plus"> create</i>
                    </button>
                </div>

                <div class="card-body bg-white list-group p-0" id="pages">


                    <!-- The Modal -->
                    <div class="modal fade" id="createPage">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Create page</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <label for="pageName"> Page name :</label>
                                    <input type="text" class="input form-control" name="pageName" placeholder="page name" required>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-dismiss="modal" id="savePage">Save page</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="list-group" id="listPages">

                    </div>

                </div>

            </div>





            <div class="card p-0 col-md-9 mb-3 bg-secondary" >
                <div class="card-header bg-light">
                    <h1 class="btn btn-light disabled mr-auto h1" > Page view</h1>
                </div>
                <div class="card-body bg-white" id="body" >


                </div>
            </div>

        </div>








    <script type="text/javascript">


        /** code mirror settings to show html + javascript tags**/

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

        /** preview html template**/

        $('#run').click(function ()
        {
            $('#body').children().remove();

            $("#addedscript").children().remove();
            $('#body').append($.parseHTML( html.getValue()));
            let script = document.createElement('script');
            script.append(javascript.getValue());
            $("#addedscript").append(script)

        });


        /** csrf token setup **/

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        /** store template **/

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






        $("#createPage").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: "/{{theme_id}}/pageCreate",
                data: {
                    body: $('pageName').val(),
                }
                ,
                success: function (data) {
                    var page = $("<a class=\"list-group-item list-group-item-action\"><i class=\"fa fa-trash\"></i></a>")
                    if (data.errors){
                        alert("SERVER Error")
                    }
                    else{
                        page.text((data.page.name).toString())
                        page.addClass(data.page.id + "page" )
                        $('#listPages').append(page)
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