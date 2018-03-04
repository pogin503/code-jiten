const disp_group = JSON.parse(document.getElementById('disp-group-vue').dataset.json);

var disp_group_app = new Vue({
    el: "#disp-group",
    data: Object.assign(
        {
            gridColumns: ['タイトル'],
        },
        disp_group
    )
});
