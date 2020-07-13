const Encore = require('@symfony/webpack-encore');
const path = require('path')

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('front/app', './assets/js/front/app.js')

    // You can directly include css in js file
    .addEntry('front/pages/account', './assets/js/front/pages/account.js')
    //.addEntry('page2', './assets/js/single/page2.js')

    // add entry for admin
    .addEntry('admin/app', './assets/js/admin/admin.js')
    .addEntry('admin/pages/maintenance', './assets/js/admin/pages/maintenance.js')

    // add entry for helpers
    .addEntry('helpers/toaster', './assets/js/helpers/toaster.js')

    .copyFiles([
        { from: './node_modules/ckeditor/', to: 'helpers/ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false },
        { from: './node_modules/ckeditor/adapters', to: 'helpers/ckeditor/adapters/[path][name].[ext]' },
        { from: './node_modules/ckeditor/lang', to: 'helpers/ckeditor/lang/[path][name].[ext]' },
        { from: './node_modules/ckeditor/plugins', to: 'helpers/ckeditor/plugins/[path][name].[ext]' },
        { from: './node_modules/ckeditor/skins', to: 'helpers/ckeditor/skins/[path][name].[ext]' }
    ])

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // enables Vuejs support
    .enableVueLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    .enableIntegrityHashes(Encore.isProduction())

    .enablePostCssLoader((options) => {
        options.config = {
            // the directory where the postcss.config.js file is stored
            path: path.resolve(__dirname)
        };
    })

    // uncomment if you're having problems with a jQuery plugin
    // .autoProvidejQuery()

    // .configureUrlLoader({
    //     fonts: { limit: 4096 },
    //     images: { limit: 4096 }
    // })

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
    ;

let config = Encore.getWebpackConfig();

config.resolve.alias["@@js"] = path.resolve(__dirname, 'assets/js'); // To access assets/js => @@js
config.resolve.alias["@@css"] = path.resolve(__dirname, 'assets/css'); // To access assets/css => @@css
config.resolve.alias["@@public"] = path.resolve(__dirname, 'public'); // To access public => @@public
config.resolve.alias["@@vendor"] = path.resolve(__dirname, 'vendor'); // To access public => @@vendor

module.exports = config;

// module.exports = Encore.getWebpackConfig();
