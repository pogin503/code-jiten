const dispGroup = JSON.parse(document.getElementById('disp-group-vue').dataset.json);

const dispGroupApp = new Vue({
    el: "#disp-group",
    data: Object.assign(dispGroup, {
        gridColumns: ['グループ名', '階層', 'desc', '表示フラグ'],
        delete_target: [],
    }),
    methods: {
        add: function(event) {
            dispGroupApp.$data.items.push({
                insert_flag: true,
            });
            return false;
        },
        remove: function(index, group_cd) {
            dispGroupApp.$data.items.splice(index, 1);
            dispGroupApp.$data.delete_target.push({ "group_cd": group_cd });
        },
    }
});
