git subsplit init git@github.com:joomla/joomla-framework.git
git subsplit publish "
	src/Joomla/Application:git@github.com:joomla/joomla-framework-application.git
	src/Joomla/Archive:git@github.com:joomla/joomla-framework-archive.git
	src/Joomla/Cache:git@github.com:joomla/joomla-framework-cache.git
	src/Joomla/Compat:git@github.com:joomla/joomla-framework-compat.git
	src/Joomla/Controller:git@github.com:joomla/joomla-framework-controller.git
	src/Joomla/Crypt:git@github.com:joomla/joomla-framework-crypt.git
	src/Joomla/Data:git@github.com:joomla/joomla-framework-data.git
	src/Joomla/Database:git@github.com:joomla/joomla-framework-database.git
	src/Joomla/Date:git@github.com:joomla/joomla-framework-date.git
	src/Joomla/DI:git@github.com:joomla/joomla-framework-di.git
	src/Joomla/Event:git@github.com:joomla/joomla-framework-event.git
	src/Joomla/Facebook:git@github.com:joomla/joomla-framework-facebook.git
	src/Joomla/Filesystem:git@github.com:joomla/joomla-framework-filesystem.git
	src/Joomla/Filter:git@github.com:joomla/joomla-framework-filter.git
	src/Joomla/Form:git@github.com:joomla/joomla-framework-form.git
	src/Joomla/Github:git@github.com:joomla/joomla-framework-github.git
	src/Joomla/Google:git@github.com:joomla/joomla-framework-google.git
	src/Joomla/Http:git@github.com:joomla/joomla-framework-http.git
	src/Joomla/Image:git@github.com:joomla/joomla-framework-image.git
	src/Joomla/Input:git@github.com:joomla/joomla-framework-input.git
	src/Joomla/Keychain:git@github.com:joomla/joomla-framework-keychain.git
	src/Joomla/Language:git@github.com:joomla/joomla-framework-language.git
	src/Joomla/Linkedin:git@github.com:joomla/joomla-framework-linkedin.git
	src/Joomla/Log:git@github.com:joomla/joomla-framework-log.git
	src/Joomla/Model:git@github.com:joomla/joomla-framework-model.git
	src/Joomla/Oauth1:git@github.com:joomla/joomla-framework-oauth1.git
	src/Joomla/Oauth2:git@github.com:joomla/joomla-framework-oauth2.git
	src/Joomla/Profiler:git@github.com:joomla/joomla-framework-profiler.git
	src/Joomla/Registry:git@github.com:joomla/joomla-framework-registry.git
	src/Joomla/Router:git@github.com:joomla/joomla-framework-router.git
	src/Joomla/Session:git@github.com:joomla/joomla-framework-session.git
	src/Joomla/String:git@github.com:joomla/joomla-framework-string.git
	src/Joomla/Test:git@github.com:joomla/joomla-framework-test.git
	src/Joomla/Twitter:git@github.com:joomla/joomla-framework-twitter.git
	src/Joomla/Uri:git@github.com:joomla/joomla-framework-uri.git
	src/Joomla/Utilities:git@github.com:joomla/joomla-framework-utilities.git
	src/Joomla/View:git@github.com:joomla/joomla-framework-view.git
" --heads="master"
rm -rf .subsplit/
