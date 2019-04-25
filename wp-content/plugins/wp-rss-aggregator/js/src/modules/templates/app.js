import axios from 'axios'
import toasted from 'vue-toasted'
import VueTippy from 'vue-tippy'

import Vuex from 'vuex'
import List from './List'
import Edit from './Edit'

import makeRouterApp from 'app/components/RouterApp'
import Router from 'app/libs/Router'
import templates from './store'
import NotificationCenter from 'app/libs/NotificationCenter'

/**
 * Main application's container.
 */
export default {
  register (services) {
    /*
     * Application router instance.
     */
    services['router'] = ({ document }) => {
      return new Router([{
        route: WpraGlobal.templates_url_base + '&action',
        name: 'templates-form',
        component: Edit,
      }, {
        route: WpraGlobal.templates_url_base,
        name: 'templates',
        component: List,
      }], {
        afterNavigating: () => {
          document.querySelector('html').scrollTop = 0
        }
      })
    }

    /*
     * Application with client side routes.
     */
    services['App'] = (container) => {
      return makeRouterApp(container)
    }

    /*
     * Setup and register central storage management.
     */
    services['vuex'] = ({ vue }) => {
      vue.use(Vuex)
      return Vuex
    }

    services['notification'] = ({ vue }) => {
      vue.use(toasted, {
        position: 'top-center',
        duration: 4000,
        iconPack: 'callback'
      })
      return new NotificationCenter(vue.toasted.show, vue.toasted.error)
    }

    services['store'] = ({ vuex }) => {
      return new vuex.Store({
        modules: {
          templates
        },
        state: {}
      })
    }

    services['http'] = () => {
      /*
       * Create authorized client for requests when nonce
       * exists in global WPRA variable.
       */
      let httpClientOptions = !!WpraGlobal && !!WpraGlobal.nonce ? {
        headers: {
          'X-WP-Nonce': WpraGlobal.nonce,
        }
      } : {}
      return axios.create(httpClientOptions)
    }

    return services
  },
  run ({ container }) {
    /*
     * Enable tippy.js tooltips.
     */
    container.vue.use(VueTippy, {
      theme: 'light',
      animation: 'fade',
      arrow: true,
      arrowTransform: 'scale(0)',
      placement: 'right'
    })
  },
}
