window.mediaView = {
    name          : "mediaVeiw",
    mimetype      : null,
    info          : null,
    div_layer     : null,
    mode          : "open",
    category_list : ["board", "jexcel"],
    category      : null,

    getName: function ()
    {
        return this.name
    },

    // 미디어 뷰어 모듈 생성자
    mediaConstructor: function ()
    {
        // 툴팁 숨김 처리
        if ($(".b_tooltip").css("display") == "block")
        {
            $(".b_tooltip").css("display", "none");
        }
    },

    // 이미지가 추가/변경 되었을 때 반복적으로 실행되어 이미지에 이벤트를 부여하는 매서드
    init: function ()
    {
        this.mediaConstructor();

        var mediaModule = this;

        $("img.show_media_contents").off("click").on("click", function () {
            //이미지 셀렉트 예외 처리
            if (typeof(this.dataset.data_info) != "undefined")
            {
                mediaModule.info = JSON.parse(this.dataset.data_info);

                mediaModule.mimetype = mediaModule.info["FILE_MIMETYPE"];
                mediaModule.mediaController();
            }
        });
        this.mediaDestructor();
    },

    // 조건, 반복 로직 및 미디어 제어 컨트롤
    mediaController: function ()
    {
        //mimetype reg matching
        if (this.mimetype.match(/image/g))
        {
            var contents_layer = this.createElementForImage();
        }
        else if (this.mimetype.match(/video/g))
        {
            var contents_layer = this.createElementForVideo();
        }
        else
        {
            return false; // var contents_layer = this.createElementForOther();
        }

        switch (this.mode)
        {
            case "open":
                //set layer to document
                this.setLayer(contents_layer);
                this.setMediaInfo();
                break;
            case "change":
                this.setLayer(contents_layer);
                break;
        }

        this.setMediaEventListener();

        return ;
    },
    // 레이어 구조에 필요한 DIV 생성
    createLayerForMedia: function ()
    {
        var layer = [];

        var div_media_dim            = document.createElement("div");
        div_media_dim.tabIndex       = "1";
        div_media_dim.className      = "div_media_dim";
        div_media_dim.id             = "div_media_dim";

        var div_media_contents       = document.createElement("div");
        div_media_contents.className = "div_media_contents";
        div_media_contents.id        = "div_media_contents";

        var div_media_info       = document.createElement("div");
        div_media_info.className = "div_media_info";
        div_media_info.id        = "div_media_info";

        var div_media_info_header = document.createElement("div");
        div_media_info_header.className = "div_media_info_header";
        div_media_info_header.id        = "div_media_info_header";

        var div_media_info_body   = document.createElement("div");
        div_media_info_body.className = "div_media_info_body";
        div_media_info_body.id        = "div_media_info_body";

        div_media_info.appendChild(div_media_info_header);
        div_media_info.appendChild(div_media_info_body);

        layer.push(div_media_dim);
        layer.push(div_media_contents);
        layer.push(div_media_info);

        this.div_layer = layer;
    },

    //Image file type에 대한 element 및 레이어 구조 생성
    createElementForImage: function ()
    {
        var media_contetns = document.createElement("div");
        media_contetns.className = "media_contetns";
        media_contetns.id        = "media_contetns";

        var image = document.createElement("img");
        image.className                 = "img-responsive media_object";
        image.src                       = "/data/"+this.info["FILE_PATH"]+this.info["FILE_NAME"];
        image.style.height              = this.info["FILE_HEIGHT"];
        image.style.width               = this.info["FILE_WIDTH"];
        image.style.background          = "black";
        image.dataset.mimetype          = this.info["FILE_MIMETYPE"];
        image.dataset.name              = this.info["FILE_NAME"];
        image.dataset.member_seq        = this.info["MEMBER_SEQ"];
        image.dataset.seq               = this.info["SEQ"];
        image.dataset.upload_file_name  = this.info["UPLOAD_FILE_NAME"];

        return media_contetns.appendChild(image);
    },

    //Video file type에 대한 element 및 레이어 구조 생성
    createElementForVideo: function ()
    {
        var media_contetns = document.createElement("div");
        media_contetns.className = "media_contetns";
        media_contetns.id        = "media_contetns";

        var video = document.createElement("video");
        video.className                 = "media_object";
        video.src                       = "/data/"+this.info["FILE_PATH"]+this.info["FILE_NAME"];
        video.style.height              = "auto";
        video.style.width               = "600px";
        video.style.background          = "black";
        video.controls                  = true;
        video.dataset.mimetype          = this.info["FILE_MIMETYPE"];
        video.dataset.name              = this.info["FILE_NAME"];
        video.dataset.member_seq        = this.info["MEMBER_SEQ"];
        video.dataset.seq               = this.info["SEQ"];
        video.dataset.upload_file_name  = this.info["UPLOAD_FILE_NAME"];

        return media_contetns.appendChild(video);
    },

    //Other file type에 대한 element 및 레이어 구조 생성
    createElementForOther: function ()
    {
        return false;

        var media_contetns = document.createElement("div");
        media_contetns.className = "media_contetns";
        media_contetns.id        = "media_contetns";

        var h5 = document.createElement("h5");
        h5.className = "media_object_text";
        h5.innerHTML    = "파일정보";

        var table = document.createElement("table");
        table.className                 = "table media_object";
        table.style.height              = "auto";
        table.style.width               = "400px";

        var thead = document.createElement("thead");
        var tr              = document.createElement("tr");
        var th_key          = document.createElement("th");
        var th_value        = document.createElement("th");
        th_key.innerHTML    = "#";
        th_value.innerHTML  = "내용";

        tr.appendChild(th_key);
        tr.appendChild(th_value);
        thead.appendChild(tr);

        var tbody = document.createElement("tbody");

        for (var key in this.info) {
            var tr              = document.createElement("tr");
            var td_key          = document.createElement("td");
            var td_value        = document.createElement("td");

            td_key.innerHTML    = key;
            td_value.innerHTML  = this.info[key];

            tr.appendChild(td_key);
            tr.appendChild(td_value);
            tbody.appendChild(tr);
        }
        table.appendChild(thead);
        table.appendChild(tbody);

        media_contetns.appendChild(h5);
        media_contetns.appendChild(table);

        return media_contetns;
    },

    // layer 있는지 없는지, 없으면 생성 후 body에 삽입
    setLayer: function (contents_layer)
    {
        if ($(".div_media_dim").length === 0)
        {
            this.createLayerForMedia();

            $(this.div_layer[1]).append(contents_layer);
            $(this.div_layer[0]).append(this.div_layer[1]).append(this.div_layer[2]);
            $("body").append(this.div_layer[0]);
        }
        else
        {
            $(".div_media_contents").html("");
            $(".div_media_contents").append(contents_layer);
        }
        $(".div_media_dim").fadeIn();
    },

    // 해당 미디어 관련 연관 리스트 생성 및 레이어에 삽입
    setMediaInfo: function ()
    {
        //loader
        var settings = { //global
            type: "outer",
            spin_second: "0.5s",
            dim_top: "56px",
            loader_margin: "-150px 0px 0px 0px"
        };
        window.loader.create(settings);

        var media_info = this.info;

        var div_header_contents       = document.createElement("div");
        div_header_contents.className = "div_header_contents";

        var title       = document.createElement("p");
        title.innerHTML = this.info["UPLOAD_FILE_NAME"];

        var btn_function_div       = document.createElement("div");
        btn_function_div.className = "btn_function_div";

        //media close
        var btn_function_close       = document.createElement("a");
        btn_function_close.className = "btn_function_close";
        btn_function_close.id        = "btn_function_close";
        btn_function_close.dataset.title = "닫기";
        btn_function_close.innerHTML = '<span class="glyphicon glyphicon-remove"></span>';

        btn_function_div.appendChild(btn_function_close);

        //media main image resize full
        var btn_function_scale       = document.createElement("a");
        btn_function_scale.className = "btn_function_scale";
        btn_function_scale.id        = "btn_function_scale";
        btn_function_scale.dataset.title = "확대";
        btn_function_scale.innerHTML = '<span class="glyphicon glyphicon-resize-full"></span>';

        btn_function_div.appendChild(btn_function_scale);

        if (this.mimetype.match(/image/g) == null && this.mimetype.match(/video/g) == null)
        {
            //if add button in type 'file' will be work to download
            var btn_function_down           = document.createElement("a");
            btn_function_down.className     = "btn_function_down";
            btn_function_down.id            = "btn_function_down";
            btn_function_down.dataset.title = "다운로드";
            btn_function_down.href          = "/data/"+this.info["FILE_PATH"]+this.info["FILE_NAME"];
            btn_function_down.download      = this.info["UPLOAD_FILE_NAME"];
            btn_function_down.innerHTML     = '<span class="glyphicon glyphicon-download-alt"></span>';

            btn_function_div.appendChild(btn_function_down);
        }

        div_header_contents.appendChild(title);
        div_header_contents.appendChild(btn_function_div);


        var div_body_contents        = document.createElement("div");
        div_body_contents.className  = "div_body_contents";

        var thum_title_div           = document.createElement("div");
        thum_title_div.className     = "thum_title_div";

        var thum_title_left          = document.createElement("span");
        thum_title_left.className    = "thum_title_left";
        thum_title_left.innerHTML    = "연관 목록";
        thum_title_left.style.float  = "left";

        var thum_title_right         = document.createElement("span");
        thum_title_left.className    = "thum_title_right";
        thum_title_right.innerHTML   = "1/1";
        thum_title_right.style.float = "right";

        thum_title_div.appendChild(thum_title_left);
        thum_title_div.appendChild(thum_title_right);

        div_body_contents.appendChild(thum_title_div);

        //get associated file list
        var associated_list = this.getMediaInfo();
        if (associated_list.RESULT)
        {
            var this_count, all_count = (associated_list.FILE_LIST.length);

            var thum_body_div         = document.createElement("div");
            thum_body_div.className   = "thum_body_div";

            $(associated_list.FILE_LIST).each(function(index, el) {
                var active = "";
                if (media_info.FILE_NAME == el.FILE_NAME)
                {
                    active      = " active";
                    this_count  = (index + 1);
                }

                var img       = document.createElement("img");
                img.className = "thum_image";
                img.src       = "/data/"+el.FILE_PATH+el.FILE_NAME;
                img.onerror   = function ()
                {
                    this.src='/assets/img/no_image_150x100.gif';
                };

                var div = document.createElement("div");

                div.className           = "thum_image_wrapper"+active;
                div.dataset.file_info   = el.FILE_INFO;
                div.dataset.mimetype    = el.FILE_MIMETYPE;
                div.dataset.file_name   = el.FILE_NAME;
                div.dataset.file_path   = el.FILE_PATH;
                div.dataset.upload_name = el.UPLOAD_FILE_NAME;
                div.dataset.file_size   = el.FILE_SIZE;

                div.appendChild(img);
                thum_body_div.appendChild(div);

            });
            div_body_contents.appendChild(thum_body_div);
            thum_title_right.innerHTML = this_count + " / " + all_count;
        }
        window.loader.remove();

        $(".div_media_info_header").html(div_header_contents);
        $(".div_media_info_body").html(div_body_contents);
        return ;
    },

    // media seq를 바탕으로 해당 미디어에 필요한 정보 요청 : AJAX(공용 컨트롤러에 요청)
    getMediaInfo: function ()
    {
        var result_data;

        switch (this.category) {
            case "board":
                var data = {
                    TYPE: "board",
                    BOARD_SEQ: this.getUrlParams("board_seq")
                };
                break;
            case "jexcel":
                var data = {
                    TYPE: "cglist",
                    PROJECT_SEQ: this.getUrlParams("project_seq")
                };
                break;
        }

        $.ajax({
            type        : "POST",
            dataType    : "json",
            url         : "/common/associated_file_list",
            data        : {associated_file_info: JSON.stringify(data)},
            cache       : false,
            async       : false,
            success: function(result){
                if (result)
                {
                    result_data = result;
                }
                else {
                    result_data = null;
                }
            },
            error: function(e){
                console.log("ERROR! : " + e.message + "- 개발팀에 문의해주세요.");
            },
            complete: function (e)
            {
                window.loader.remove();
            }
        });
        return result_data;
    },

    thumnailMove: function (file_info)
    {
        var mediaModule = this;

        //파일타입 재정의 및 컨트롤러 호출
        mediaModule.info = JSON.parse(file_info);
        mediaModule.mimetype = mediaModule.info["FILE_MIMETYPE"];
        mediaModule.mediaController();

        // 로더 생성
        var settings = {
            target          : "#div_media_info_header > div > p",
            type            : "inner",
            spin_second     : "0.5s",
            width           : "10px",
            height          : "10px",
            loader_margin   : "0px 10px 0px 0px"
        };
        window.loader.create(settings);

        // 섬네일 리스트에서의 클래스명 토글
        $(".thum_image_wrapper").removeClass('active');
        $(".thum_image_wrapper").each(function(index, el) {
            if (el.dataset.file_name == mediaModule.info["FILE_NAME"])
            {
                $(el).addClass('active');
            }
        });

        // 모든 비디오 일시정지
        $("video").each(function () { this.pause() });

        // 로더 삭제
        setTimeout(function() {
            window.loader.remove();
        }, 300);
    },

    // 메인 미디어 관련 이벤트 지정
    setMediaEventListener: function ()
    {
        var mediaModule = this;

        $('.div_media_dim')
        .off("keyup")
        .off("click")
        .on(
            {
                keyup: function (event)
                {
                    // console.log(event.keyCode);
                    // press arrow top
                    if (event.keyCode == 37)
                    {
                        // 기본 이벤트 실행 안함
                        event.preventDefault();
                        var file_info = JSON.stringify($(".thum_image_wrapper.active").prev().data("file_info"));
                        if (file_info != null)
                        {
                            mediaModule.thumnailMove(file_info);
                        }
                    }
                    // press arrow bottom
                    if (event.keyCode == 39)
                    {
                        event.preventDefault();
                        var file_info = JSON.stringify($(".thum_image_wrapper.active").next().data("file_info"));
                        if (file_info != null)
                        {
                            mediaModule.thumnailMove(file_info);
                        }
                    }
                    // press esc
                    if (event.keyCode == 27)
                    {
                        event.preventDefault();
                        if ($(".expansion_div").length != 0)
                        {
                            $(".expansion_div").remove();
                        }
                        else if ($(".div_media_dim").length != 0)
                        {
                            mediaModule.mediaClose();
                        }

                    }
                },
                click: function (event) {
                    // 이벤트 전파 방지
                    event.stopPropagation();

                    mediaModule.mediaClose();
                }
            }
        ).focus();

        $(".media_object")
            .off("click")
            .on("click", function (event)
                {
                    event.stopPropagation();
                }
            );

        $(".div_media_info")
            .off("click")
            .on("click", function (event)
                {
                    event.stopPropagation();
                }
            );

        // 섬네일 클릭 시 이벤트 지정
        $(".thum_image_wrapper")
            .off("click")
            .on("click", function (event)
                {
                    mediaModule.thumnailMove(this.dataset.file_info);
                }
            );

        //컨트롤 메뉴의 닫기 버튼 이벤트 세팅
        $("#btn_function_close")
            .off("click")
            .on("click", function (event)
                {
                    mediaModule.mediaClose();
                }
            );

        //컨트롤 메뉴의 닫기 버튼 이벤트 세팅
        $("#btn_function_scale")
            .off("click")
            .on("click", function (event)
                {
                    mediaModule.mediaScale();
                }
            );

        //전체화면 닫기 버튼 이벤트 세팅
        $(".div_close_expansion")
            .off("click")
            .on("click", function (event)
                {
                    $(".expansion_div").remove();
                }
            );
    },

    mediaClose: function ()
    {
        $(".div_media_dim").fadeOut(300, function () {
            $(this).remove();
        });
    },

    mediaScale: function ()
    {
        var expansion_div = document.createElement("div");
        expansion_div.className = "expansion_div";

        if (this.mimetype.match(/image/g))
        {
            var object = document.createElement("img");
            object.style.width  = this.info["FILE_WIDTH"];
            object.style.height = this.info["FILE_HEIGHT"];
        }
        else if (this.mimetype.match(/video/g))
        {
            var object = document.createElement("video");
            object.style.width  = "800px";
            object.style.height = "auto";
            object.controls = true;
        }
        object.className = "expansion_object";
        object.src = "/data/"+this.info["FILE_PATH"]+this.info["FILE_NAME"];

        var div_close_expansion = document.createElement("div");
        div_close_expansion.className = "div_close_expansion";

        var btn_close_expansion = document.createElement("a");
        btn_close_expansion.className = "btn_close_expansion";
        btn_close_expansion.innerHTML = '<span class="glyphicon glyphicon-remove"></span>';

        div_close_expansion.appendChild(btn_close_expansion);

        expansion_div.appendChild(div_close_expansion);
        expansion_div.appendChild(object);

        $("body").append(expansion_div);

        return this.setMediaEventListener();
    },

    // 미디어 모듈 소멸자(이벤트 후처리 등)
    mediaDestructor: function ()
    {
        // 툴팁 숨김 처리
        $(".b_tooltip").css("display", "none");
    },

    //get parameter
    getUrlParams : function(name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
            return null;
        }
        else{
            return results[1] || 0;
        }
    }
};
$( document ).ready(function() {
    $(mediaView.category_list).each(function(index, el) {
        var pattern = new RegExp(el);
        if (location.href.match(pattern) &&
                (
                    $("#content").find("img").length  != 0 ||
                    $(".contents").find("img").length != 0
                )
            )
        {
            console.log("MediaViewer Setup Complate!");
            // ajax를 통해 정보를 받아오기 위한 현재 페이지의 타입 지정
            mediaView.category = el;

            //미디어 뷰어 설치
            mediaView.init();
        }
    });
});


