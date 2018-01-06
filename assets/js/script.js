const json = JSON.parse(document.getElementById('json-vue').dataset.json);

// console.log(json);

// var app = new Vue({
//     el: '#app',
//     data: json,
//     methods: {
//         add: function (event) {
//             v.$data.items.push({});
//             return false;
//         }
//     }
// });

var app2 = new Vue({
    el: '#app-2',
    data: {
        message2: 'You loaded this page on ' + new Date().toLocaleString()
    }
});

var app3 = new Vue({
    el: '#app-3',
    data: {
        seen: true
    }
});
const x = {todos: [
    { text: 'Learn JavaScript' },
    { text: 'Learn Vue' },
    { text: 'Build something awesome' }
]};
console.log(x);

var app4 = new Vue({
    el: '#app-4',
    data: {
        todos: [
            { text: 'Learn JavaScript' },
            { text: 'Learn Vue' },
            { text: 'Build something awesome' }
        ]
    }
});

var app5 = new Vue({
    el: '#app-5',
    data: {
        message5: 'Hello Vue.js!'
    },
    methods: {
        reverseMessage: function () {
            this.message5 = this.message5.split('').reverse().join('');
        }
    }
});

var app6 = new Vue({
    el: '#app-6',
    data: {
        message6: 'Hello Vue!'
    }
});
