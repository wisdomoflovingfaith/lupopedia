(function() {
    function ChannelsCommunication(basePath, csrfToken) {
        this.basePath = basePath || '';
        this.csrfToken = csrfToken || '';
    }

    ChannelsCommunication.prototype.fetchJson = function(endpoint, method, payload, callback) {
        var options = {
            method: method || 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        };
        if (this.csrfToken) {
            options.headers['X-CSRF-Token'] = this.csrfToken;
        }
        if (payload) {
            options.body = JSON.stringify(payload);
        }

        fetch(this.basePath + endpoint, options)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (callback) {
                    callback(null, data);
                }
            })
            .catch(function(err) {
                if (callback) {
                    callback(err, null);
                }
            });
    };

    ChannelsCommunication.prototype.listOperators = function(channelId, callback) {
        var query = channelId ? ('?channel_id=' + encodeURIComponent(channelId)) : '';
        return this.fetchJson('operators' + query, 'GET', null, callback);
    };

    ChannelsCommunication.prototype.createOperator = function(data, callback) {
        return this.fetchJson('operators', 'POST', data || {}, callback);
    };

    ChannelsCommunication.prototype.updateOperator = function(authUserId, data, callback) {
        return this.fetchJson('operators/' + encodeURIComponent(authUserId), 'PUT', data || {}, callback);
    };

    ChannelsCommunication.prototype.deleteOperator = function(authUserId, data, callback) {
        return this.fetchJson('operators/' + encodeURIComponent(authUserId), 'DELETE', data || {}, callback);
    };

    ChannelsCommunication.prototype.listDepartments = function(callback) {
        return this.fetchJson('departments', 'GET', null, callback);
    };

    ChannelsCommunication.prototype.createDepartment = function(data, callback) {
        return this.fetchJson('departments', 'POST', data || {}, callback);
    };

    ChannelsCommunication.prototype.updateDepartment = function(departmentId, data, callback) {
        return this.fetchJson('departments/' + encodeURIComponent(departmentId), 'PUT', data || {}, callback);
    };

    ChannelsCommunication.prototype.deleteDepartment = function(departmentId, data, callback) {
        return this.fetchJson('departments/' + encodeURIComponent(departmentId), 'DELETE', data || {}, callback);
    };

    ChannelsCommunication.prototype.getSettings = function(channelId, callback) {
        return this.fetchJson('settings/' + encodeURIComponent(channelId), 'GET', null, callback);
    };

    ChannelsCommunication.prototype.updateSettings = function(channelId, data, callback) {
        return this.fetchJson('settings/' + encodeURIComponent(channelId), 'PUT', data || {}, callback);
    };

    window.ChannelsCommunication = ChannelsCommunication;
})();
