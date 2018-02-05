const dispGroup = JSON.parse(document.getElementById('disp-group-vue').dataset.json);

const dispGroupApp = new Vue({
    el: "#disp-group",
    data: Object.assign(dispGroup, {
        gridColumns: ['グループ名', '階層', 'desc', '表示フラグ'],
    }),
    methods: {
        add: function(event) {
            dispGroupApp.$data.items.push({
                insert_flag: true,
            });
            return false;
        },
    }
});
