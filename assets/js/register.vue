const disp_group = JSON.parse(document.getElementById('disp-group-vue').dataset.json);

var disp_group_app = new Vue({
    el: "#disp-group",
    data: disp_group,
});

function isJSON(arg) {
    arg = (typeof arg === "function") ? arg() : arg;
    if (typeof arg  !== "string") {
        return false;
    }
    try {
        arg = (!JSON) ? eval("(" + arg + ")") : JSON.parse(arg);
        return true;
    } catch (e) {
        return false;
    }
};

const AutosizeTextarea = {
    props: [ 'value' ],
    template: '<textarea rows="3" cols="60">{{ value }}</textarea>',
    mounted: function () {
        /* var num = this.value.split("\n").length;
         * if (num > 4) {
         *   autosize(this.$el);
         * }*/
        autosize(this.$el);
    }
};

const languages = JSON.parse(document.getElementById('languages-vue').dataset.json);

if (isJSON(document.getElementById('group-vue').dataset.json)) {
    const group_set = JSON.parse(document.getElementById('group-vue').dataset.json);
    const json = JSON.parse(document.getElementById('json-vue').dataset.json);
    var v = new Vue({
        components: {
            'autosize-textarea': AutosizeTextarea
        },
        el: '#app',
        data: Object.assign({}, group_set, json),
        methods: {
            add: function (event) {
                v.$data.items.push(
                    Object.assign({}, group_set, {
                        example: '',
                        insert_flag: true,
                        update_flag: false,
                        delete_flag: false,
                        show_flag: true,
                        languages: languages,
                        row_num: v.$data.items.length + 1,
                    }));
                return false;
            },
            remove: function(event) {
                this.show_flag = false;
                this.delete_flag = true;
                console.log(this);
                return false;
            }
        }
    });
}
