const dispGroup = JSON.parse(document.getElementById('disp-group-vue').dataset.json);
const groupNames = dispGroup.items.map(function (element) {
    return {
        "group_cd": element["group"]["group_cd"],
        "group_name": element["group"]["group_name"],
    };
});

const dispGroupApp = new Vue({
    el: "#disp-group",
    data: Object.assign(dispGroup,
                        {
                            show_flag: false,
                            gridColumns: ['グループ名', '親グループ', 'desc', '表示フラグ'],
                            delete_target: [],
                            insert_target: [],
                            group_names: groupNames,
                        }
                       ),
    methods: {
        add: function(event) {
            dispGroupApp.$data.insert_target.push({
                group: '',
                insert_flag: true,
                parent_id: -1,
            });
            dispGroupApp.$data.show_flag = true;
            return false;
        },
        remove: function(index, group_cd) {
            dispGroupApp.$data.items.splice(index, 1);
            dispGroupApp.$data.delete_target.push({ "group_cd": group_cd });
        },
    }
});
