git subsplit init git@github.com:joomla/joomla-framework.git
git subsplit publish "
	vendor/Joomla/Application:git@github.com:joomla/joomla-framework-application.git
	vendor/Joomla/Archive:git@github.com:joomla/joomla-framework-archive.git
	vendor/Joomla/Cache:git@github.com:joomla/joomla-framework-cache.git
	vendor/Joomla/Client:git@github.com:joomla/joomla-framework-client.git
	vendor/Joomla/Compat:git@github.com:joomla/joomla-framework-compat.git
	vendor/Joomla/Controller:git@github.com:joomla/joomla-framework-controller.git
	vendor/Joomla/Crypt:git@github.com:joomla/joomla-framework-crypt.git
	vendor/Joomla/Data:git@github.com:joomla/joomla-framework-data.git
	vendor/Joomla/Database:git@github.com:joomla/joomla-framework-database.git
	vendor/Joomla/Date:git@github.com:joomla/joomla-framework-date.git
	vendor/Joomla/Filesystem:git@github.com:joomla/joomla-framework-filesystem.git
	vendor/Joomla/Filter:git@github.com:joomla/joomla-framework-filter.git
	vendor/Joomla/Form:git@github.com:joomla/joomla-framework-form.git
	vendor/Joomla/Github:git@github.com:joomla/joomla-framework-github.git
	vendor/Joomla/Google:git@github.com:joomla/joomla-framework-google.git
	vendor/Joomla/Http:git@github.com:joomla/joomla-framework-http.git
	vendor/Joomla/Image:git@github.com:joomla/joomla-framework-image.git
	vendor/Joomla/Input:git@github.com:joomla/joomla-framework-input.git
	vendor/Joomla/Keychain:git@github.com:joomla/joomla-framework-keychain.git
	vendor/Joomla/Language:git@github.com:joomla/joomla-framework-language.git
	vendor/Joomla/Log:git@github.com:joomla/joomla-framework-log.git
	vendor/Joomla/Model:git@github.com:joomla/joomla-framework-model.git
	vendor/Joomla/Oauth2:git@github.com:joomla/joomla-framework-oauth2.git
	vendor/Joomla/Profiler:git@github.com:joomla/joomla-framework-profiler.git
	vendor/Joomla/Registry:git@github.com:joomla/joomla-framework-registry.git
	vendor/Joomla/Session:git@github.com:joomla/joomla-framework-session.git
	vendor/Joomla/String:git@github.com:joomla/joomla-framework-string.git
	vendor/Joomla/Test:git@github.com:joomla/joomla-framework-test.git
	vendor/Joomla/Uri:git@github.com:joomla/joomla-framework-uri.git
	vendor/Joomla/Utilities:git@github.com:joomla/joomla-framework-utilities.git
	vendor/Joomla/View:git@github.com:joomla/joomla-framework-view.git
"
rm -rf .subsplit
