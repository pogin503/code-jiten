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

if (isJSON(document.getElementById('group-vue').dataset.json)) {
    const group_set = JSON.parse(document.getElementById('group-vue').dataset.json);
    const group_names = JSON.parse(document.getElementById('group-names-vue').dataset.json);
    const json = JSON.parse(document.getElementById('json-vue').dataset.json);

    var v = new Vue({
        components: {
            'autosize-textarea': AutosizeTextarea
        },
        el: '#app',
        data: Object.assign(
            {
                gridColumns: ['言語', '実行例', ''],
                delete_target: [],
            },
            group_set,
            json,
            group_names
        ),
        methods: {
            add: function (event) {
                v.$data.items.push(
                    Object.assign({}, group_set, {
                        example: { language: '' },
                        insert_flag: true,
                        update_flag: false,
                        show_flag: true,
                        row_num: v.$data.items.length + 1,
                    }));
                return false;
            },
            remove: function(index, example_id) {
                // this.show_flag = false;
                if(confirm("Are you sure?")) {
                    v.$data.items.splice(index, 1);
                    v.$data.delete_target.push({"example_id": example_id});
                }
                return false;
            }
        }
    });
}
