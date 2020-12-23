var notice = notice || {};

notice.showNotice = function (autoTitle, msg) {
    document.addEventListener('DOMContentLoaded', initNotice(autoTitle, msg));
};

function initNotice(autoTitle, msg) {
    const common = {
        loadResource: function (id, resource, type) {
            return new Promise(function (resolve, reject) {
                let loaded = document.head.querySelector('#' + id);
                if (loaded) {
                    resolve('success: ' + resource);
                    return;
                }
                const element = document.createElement(type);
                element.onload = element.onreadystatechange = () => {
                    if (!loaded && (!element.readyState || /loaded|complete/.test(element.readyState))) {
                        element.onload = element.onreadystatechange = null;
                        loaded = true;
                        resolve('success: ' + resource);
                    }
                }
                element.onerror = function () {
                    reject(Error(resource + ' load error!'));
                };
                if (type === 'link') {
                    element.rel = 'stylesheet';
                    element.href = resource;
                } else {
                    element.src = resource;
                }
                element.id = id;
                document.getElementsByTagName('head')[0].appendChild(element);
            });
        },
        loadResources: function () {
            const initVue = this.initVue;
            const loadResource = this.loadResource;
            const host = 'https://cdn.bootcdn.net/ajax/libs/';
            const resources = [
                'vue/2.6.12/vue.min.js',
                'element-ui/2.14.1/index.min.js',
                'element-ui/2.14.1/theme-chalk/index.min.css'
            ];
            const loadPromises = [];
            resources.forEach(resource => {
                loadPromises.push(loadResource(btoa(resource).replace(/[=+\/]/g, ''), host + resource,
                    ({
                        'css': 'link',
                        'js': 'script'
                    })[resource.split('.').pop()]
                ));
            });
            Promise.all(loadPromises).then(
                function () {
                    let flag = false;
                    const waitVue = setInterval(() => {
                        if (!flag && typeof Vue === 'function') {
                            flag = true;
                            initVue();
                            clearInterval(waitVue);
                        }
                    }, 100);
                }
            );
        },
        initVue: function () {
            var blog = new Vue({
                el: document.createElement('div'),
                created() {
                    this.sayHello();
                    window.alert = this.alert;
                },
                computed: {
                    hello: function () {
                        var hours = (new Date()).getHours()
                        var t
                        if (autoTitle == 'open') {
                            if (hours < 5) {
                                t = '凌晨了，注意休息哦！'
                            } else if (hours >= 5 && hours < 8) {
                                t = '早上好，新的一天又是元气满满呢！'
                            } else if (hours >= 8 && hours < 12) {
                                t = '上午好！'
                            } else if (hours === 12) {
                                t = '中午好！'
                            } else if (hours > 12 && hours <= 18) {
                                t = '下午好！'
                            } else if (hours > 18 && hours <= 22) {
                                t = '晚上好！'
                            } else if (hours > 22 && hours < 24) {
                                t = '夜深了，注意休息哦！'
                            }
                        }
                        else {
                            t = msg.title;
                        }
                        return t
                    }
                },
                methods: {
                    alert: function (message, title, type, duration, showClose, offset, onClose) {
                        this.$notify({
                            message: message,
                            type: type || 'error',
                            title: title || '警告',
                            duration: duration,
                            showClose: showClose || false,
                            offset: offset || 0,
                            onClose: onClose
                        });
                    },
                    sayHello: function () {
                        setTimeout(() => {
                            msg.duration = msg.duration.trim();
                            if (isNaN(msg.duration)) { msg.duration = 5000; }
                            this.alert(msg.content, this.hello, 'success', msg.duration);
                        }, 1000);
                    }
                },
            })
        }
    };
    common.loadResources();
}