window.loader = {
    //loader test
    // var settings = { //global
    //     type: "outer",
    //     spin_second: "0.5s",
    //     dim_top: "56px",
    //     loader_margin: "-150px 0px 0px 0px"
    // };
    // var settings = {
    //     target: "#content > div > div.panel-footer > div:nth-child(1) > h5",
    //     type: "inner",
    //     spin_second: "0.5s",
    //     width: "10px",
    //     height: "10px",
    //     loader_margin: "0px 10px 0px 0px"
    // };
    //
    // loader.create(settings);
    //
    // setTimeout(function() {
    //     loader.remove();
    // }, 3000);

    name: "loader",

    prop: {
        target          : "body",
        type            : "inner", // or outer
        border          : "10px",
        out_color       : "#f3f3f3",
        in_color        : "gray",
        width           : "80px",
        height          : "80px",
        dim_top         : "0px",
        dim_left        : "0px",
        dim_width       : "100%",
        dim_height      : "100%",
        loader_margin   : "0px",
        spin_second     : "1s",
        float           : "right"
    },

    getName: function () {
        return this.name;
    },

    create: function (custom) {
        if ($(".loader").length !== 0)
        {
            return false;
        }

        //set custom setting value
        this.prop = $.extend({}, this.prop, custom);

        //set loader to document
        this.setLoader2Document();
    },

    setLoader2Document: function ()
    {
        var setting;

        switch (this.prop.type) {
            case "outer":
                var dim       = document.createElement("DIV");
                var loader    = document.createElement("DIV")
                var border    = this.prop.border + " solid " + this.prop.out_color;
                var borderTop = this.prop.border+" solid "+this.prop.in_color;

                // dim setting
                dim.id = "dim";
                dim.className       = "dim";
                dim.style.width     = this.prop.dim_width;
                dim.style.height    = this.prop.dim_height;
                dim.style.top       = this.prop.dim_top;
                dim.style.left      = this.prop.dim_left;

                // loader setting
                loader.id                       = "loader";
                loader.className                = "loader";
                loader.style.width              = this.prop.width;
                loader.style.height             = this.prop.height;
                loader.style.margin             = this.prop.loader_margin;
                loader.style.border             = border;
                loader.style.borderTop          = borderTop;
                loader.style.WebkitAnimation    = "spin "+this.prop.spin_second+" linear infinite";
                loader.style.animation          = "spin "+this.prop.spin_second+" linear infinite";

                dim.appendChild(loader);

                $(this.prop.target).append(dim);

                setting = true;
                break;

            case "inner":
                var loader    = document.createElement("DIV")
                var border    = this.prop.border + " solid " + this.prop.out_color;
                var borderTop = this.prop.border+" solid "+this.prop.in_color;

                loader.id                    = "loader";
                loader.className             = "loader inner";
                loader.style.width           = this.prop.width;
                loader.style.height          = this.prop.height;
                loader.style.margin          = this.prop.loader_margin;
                loader.style.border          = border;
                loader.style.borderTop       = borderTop;
                loader.style.WebkitAnimation = "spin "+this.prop.spin_second+" linear infinite";
                loader.style.animation       = "spin "+this.prop.spin_second+" linear infinite";
                loader.style.float           = this.prop.float;

                $(this.prop.target).append(loader);

                setting = true;
                break;
        }
        return setting;
    },

    remove: function () {
        $(".dim").remove();
        $("."+this.name).remove();
    }
}

