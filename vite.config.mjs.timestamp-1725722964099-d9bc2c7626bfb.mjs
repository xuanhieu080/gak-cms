// vite.config.mjs
import laravel from "file:///C:/laragon/www/gak-cms/node_modules/laravel-vite-plugin/dist/index.js";
import { defineConfig } from "file:///C:/laragon/www/gak-cms/node_modules/vite/dist/node/index.js";
import vue from "file:///C:/laragon/www/gak-cms/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import path from "path";
import Components from "file:///C:/laragon/www/gak-cms/node_modules/unplugin-vue-components/dist/vite.js";
import { AntDesignVueResolver } from "file:///C:/laragon/www/gak-cms/node_modules/unplugin-vue-components/dist/resolvers.js";
var __vite_injected_original_dirname = "C:\\laragon\\www\\gak-cms";
var vite_config_default = defineConfig({
  define: {
    __VUE_I18N_FULL_INSTALL__: true,
    __VUE_I18N_LEGACY_API__: false,
    __INTLIFY_PROD_DEVTOOLS__: false
  },
  plugins: [
    laravel({
      input: ["resources/css/app.scss", "resources/js/app.js"],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          // The Vue plugin will re-write asset URLs, when referenced
          // in Single File Components, to point to the Laravel web
          // server. Setting this to `null` allows the Laravel plugin
          // to instead re-write asset URLs to point to the Vite
          // server instead.
          base: null,
          // The Vue plugin will parse absolute URLs and treat them
          // as absolute paths to files on disk. Setting this to
          // `false` will leave absolute URLs un-touched so they can
          // reference assets in the public directly as expected.
          includeAbsolute: false
        }
      }
    }),
    Components({
      resolvers: [
        AntDesignVueResolver({
          importStyle: false
          // css in js
        })
      ]
    })
  ],
  resolve: {
    alias: {
      "@": path.resolve(__vite_injected_original_dirname, "./resources/js")
    },
    extensions: [".js", ".ts", ".jsx", ".tsx", ".json", ".vue", ".mjs"]
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcubWpzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiQzpcXFxcbGFyYWdvblxcXFx3d3dcXFxcZ2FrLWNtc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcbGFyYWdvblxcXFx3d3dcXFxcZ2FrLWNtc1xcXFx2aXRlLmNvbmZpZy5tanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L2xhcmFnb24vd3d3L2dhay1jbXMvdml0ZS5jb25maWcubWpzXCI7aW1wb3J0IGxhcmF2ZWwgZnJvbSAnbGFyYXZlbC12aXRlLXBsdWdpbidcclxuaW1wb3J0IHtkZWZpbmVDb25maWd9IGZyb20gJ3ZpdGUnXHJcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcclxuaW1wb3J0IHBhdGggZnJvbSAncGF0aCdcclxuaW1wb3J0IENvbXBvbmVudHMgZnJvbSAndW5wbHVnaW4tdnVlLWNvbXBvbmVudHMvdml0ZSc7XHJcbmltcG9ydCB7IEFudERlc2lnblZ1ZVJlc29sdmVyIH0gZnJvbSAndW5wbHVnaW4tdnVlLWNvbXBvbmVudHMvcmVzb2x2ZXJzJztcclxuXHJcbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XHJcbiAgICBkZWZpbmU6IHtcclxuICAgICAgICBfX1ZVRV9JMThOX0ZVTExfSU5TVEFMTF9fOiB0cnVlLFxyXG4gICAgICAgIF9fVlVFX0kxOE5fTEVHQUNZX0FQSV9fOiBmYWxzZSxcclxuICAgICAgICBfX0lOVExJRllfUFJPRF9ERVZUT09MU19fOiBmYWxzZSxcclxuICAgIH0sXHJcbiAgICBwbHVnaW5zOiBbXHJcbiAgICAgICAgbGFyYXZlbCh7XHJcbiAgICAgICAgICAgIGlucHV0OiBbXCJyZXNvdXJjZXMvY3NzL2FwcC5zY3NzXCIsJ3Jlc291cmNlcy9qcy9hcHAuanMnXSxcclxuICAgICAgICAgICAgcmVmcmVzaDogdHJ1ZVxyXG4gICAgICAgIH0pLFxyXG4gICAgICAgIHZ1ZSh7XHJcbiAgICAgICAgICAgIHRlbXBsYXRlOiB7XHJcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm1Bc3NldFVybHM6IHtcclxuICAgICAgICAgICAgICAgICAgICAvLyBUaGUgVnVlIHBsdWdpbiB3aWxsIHJlLXdyaXRlIGFzc2V0IFVSTHMsIHdoZW4gcmVmZXJlbmNlZFxyXG4gICAgICAgICAgICAgICAgICAgIC8vIGluIFNpbmdsZSBGaWxlIENvbXBvbmVudHMsIHRvIHBvaW50IHRvIHRoZSBMYXJhdmVsIHdlYlxyXG4gICAgICAgICAgICAgICAgICAgIC8vIHNlcnZlci4gU2V0dGluZyB0aGlzIHRvIGBudWxsYCBhbGxvd3MgdGhlIExhcmF2ZWwgcGx1Z2luXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gdG8gaW5zdGVhZCByZS13cml0ZSBhc3NldCBVUkxzIHRvIHBvaW50IHRvIHRoZSBWaXRlXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gc2VydmVyIGluc3RlYWQuXHJcbiAgICAgICAgICAgICAgICAgICAgYmFzZTogbnVsbCxcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gVGhlIFZ1ZSBwbHVnaW4gd2lsbCBwYXJzZSBhYnNvbHV0ZSBVUkxzIGFuZCB0cmVhdCB0aGVtXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gYXMgYWJzb2x1dGUgcGF0aHMgdG8gZmlsZXMgb24gZGlzay4gU2V0dGluZyB0aGlzIHRvXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gYGZhbHNlYCB3aWxsIGxlYXZlIGFic29sdXRlIFVSTHMgdW4tdG91Y2hlZCBzbyB0aGV5IGNhblxyXG4gICAgICAgICAgICAgICAgICAgIC8vIHJlZmVyZW5jZSBhc3NldHMgaW4gdGhlIHB1YmxpYyBkaXJlY3RseSBhcyBleHBlY3RlZC5cclxuICAgICAgICAgICAgICAgICAgICBpbmNsdWRlQWJzb2x1dGU6IGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICB9KSxcclxuICAgICAgICBDb21wb25lbnRzKHtcclxuICAgICAgICAgICAgcmVzb2x2ZXJzOiBbXHJcbiAgICAgICAgICAgICAgICBBbnREZXNpZ25WdWVSZXNvbHZlcih7XHJcbiAgICAgICAgICAgICAgICAgICAgaW1wb3J0U3R5bGU6IGZhbHNlLCAvLyBjc3MgaW4ganNcclxuICAgICAgICAgICAgICAgIH0pLFxyXG4gICAgICAgICAgICBdLFxyXG4gICAgICAgIH0pLFxyXG4gICAgXSxcclxuICAgIHJlc29sdmU6IHtcclxuICAgICAgICBhbGlhczoge1xyXG4gICAgICAgICAgICAnQCc6IHBhdGgucmVzb2x2ZShfX2Rpcm5hbWUsICcuL3Jlc291cmNlcy9qcycpLFxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZXh0ZW5zaW9uczogWycuanMnLCAnLnRzJywgJy5qc3gnLCAnLnRzeCcsICcuanNvbicsICcudnVlJywgJy5tanMnXVxyXG4gICAgfVxyXG59KTtcclxuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUE4UCxPQUFPLGFBQWE7QUFDbFIsU0FBUSxvQkFBbUI7QUFDM0IsT0FBTyxTQUFTO0FBQ2hCLE9BQU8sVUFBVTtBQUNqQixPQUFPLGdCQUFnQjtBQUN2QixTQUFTLDRCQUE0QjtBQUxyQyxJQUFNLG1DQUFtQztBQU96QyxJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixRQUFRO0FBQUEsSUFDSiwyQkFBMkI7QUFBQSxJQUMzQix5QkFBeUI7QUFBQSxJQUN6QiwyQkFBMkI7QUFBQSxFQUMvQjtBQUFBLEVBQ0EsU0FBUztBQUFBLElBQ0wsUUFBUTtBQUFBLE1BQ0osT0FBTyxDQUFDLDBCQUF5QixxQkFBcUI7QUFBQSxNQUN0RCxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsSUFDRCxJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUEsVUFNaEIsTUFBTTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUEsVUFNTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxJQUNELFdBQVc7QUFBQSxNQUNQLFdBQVc7QUFBQSxRQUNQLHFCQUFxQjtBQUFBLFVBQ2pCLGFBQWE7QUFBQTtBQUFBLFFBQ2pCLENBQUM7QUFBQSxNQUNMO0FBQUEsSUFDSixDQUFDO0FBQUEsRUFDTDtBQUFBLEVBQ0EsU0FBUztBQUFBLElBQ0wsT0FBTztBQUFBLE1BQ0gsS0FBSyxLQUFLLFFBQVEsa0NBQVcsZ0JBQWdCO0FBQUEsSUFDakQ7QUFBQSxJQUNBLFlBQVksQ0FBQyxPQUFPLE9BQU8sUUFBUSxRQUFRLFNBQVMsUUFBUSxNQUFNO0FBQUEsRUFDdEU7QUFDSixDQUFDOyIsCiAgIm5hbWVzIjogW10KfQo=
