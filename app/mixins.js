/**
 * This mixins is used in couple with rg-loading.
 * The owner tag would nest the rg-loading component as following:
 *
 * <rg-loading show="{ showLoading }" spinner="true">
 *   <span data-i18n="Loading"></span>
 * </rg-loading>
 *
 * then in the owner tag script: this.mixin(LoadingMixin)
 * The LoadingMixin mixin provides 2 functions: showLoading() and hideLoading()
 *
 * @type {{showLoading: Function, hideLoading: Function}}
 */
var LoadingMixin = {
    /**
     * Show the rg-loading component in the owner tag
     * avoid screen-flicking by waiting 50ms before showing the loading-overlay,
     * the loading-overlay won't show up if the operation finish within 50ms
     * @param delay: int (default = 50ms)
     */
    showLoading: function(delay) {
        if (!delay) {
            delay = 50;
        }

        console.log("show rg-loading component");

        this._loading = true;

        var _this = this;
        setTimeout(function() {
            if (_this._loading && !_this.loading) {
                _this.loading = true;
                _this.update();
            }
        }, delay);
    },

    /**
     * Hide the rg-loading component in the owner tag
     */
    hideLoading: function () {
        this._loading = false;
        this.loading = false;
        this.update();
    }
};

module.exports.LoadingMixin = LoadingMixin;