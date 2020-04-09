var Encore = require('@symfony/webpack-encore');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');


if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore

    .setOutputPath('public/build/')

    .setPublicPath('/build')

    .enableVueLoader()

    .addEntry('app', './assets/js/app.js')


    .addEntry('vue', './assets/vue/main.js')
    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]'
    })

    .splitEntryChunks()

    .enableSingleRuntimeChunk()


    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    // add VuetifyLoaderPlugin
    .addPlugin(new VuetifyLoaderPlugin())

    // enables Sass/SCSS support
    .enableSassLoader(options => {
        options.implementation = require('sass'),
        options.fiber = require('fibers')
    })

;

module.exports = Encore.getWebpackConfig();
