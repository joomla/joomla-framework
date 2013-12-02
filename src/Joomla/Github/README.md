# The Github Package

## Using the Github Package

The Github package is designed to be a straightforward interface for working with Github. It is based on version 3 of
the Github API. You can find documentation on the API at [http://developer.github.com/v3/.]
(http://developer.github.com/v3/)

Github is built upon the Http package which provides an easy way to consume URLs and web services in a transport
independent way. `Joomla\Http` currently supports streams, sockets and cURL. It is possible to create a custom
context and inject it into the Github class if one so desires.

### Instantiating Github

Instantiating Github is easy:

```php
use Joomla\Github\Github;

$github = new Github;
```

This creates a basic Github object that can be used to access publicly available resources on [github.com]
(https://github.com).

Sometimes it is necessary to specify additional options. This can be done by injecting in a Registry object with your
preferred options. Support is available for optionally providing a custom Github account username and password,
as well as a custom URL for the Github server (as would be the case for using a local instance of [Github Enterprise]
(https://enterprise.github.com)).

```php
use Joomla\Github\Github;
use Joomla\Registry\Registry;

$options = new Registry;
$options->set('api.username', 'github_username');
$options->set('api.password', 'github_password');
$options->set('api.url', 'http://github.enterprise.example.com');

$github = new Github($options);
```

A `gh.token` option is also available.

Here is an example demonstrating more of the Github package:

```php
use Joomla\Github\Github;
use Joomla\Registry\Registry;

$options = new Registry;
$options->set('api.username', 'github_username');
$options->set('api.password', 'github_password');
$options->set('api.url', 'http://myhostedgithub.example.com');

$github = new Github($options);

// get a list of all the user's issues
$issues = $github->issues->getList();

$issueSummary = array();

foreach ($issues as $issue)
{
    $issueSummary[] = '+ ' . $issue->title;
}

$summary = implode("\n", $issueSummary);

$github->gists->create(array('issue_summary.txt' => $summary));
```

## Accessing the Github APIs

The Github object using magic methods to access sub-packages of the Github server's API that can be accessed using
the `->` object operator.

Where a result is returned by a PHP method, the result is the PHP equivalent of the JSON response that can be found in
the Github API documentation.

### Activity

See http://developer.github.com/v3/activity/.

#### Events

See http://developer.github.com/v3/activity/events/.

```php
// List public events.
$events = $github->activity->events->getPublic();

// List repository events.
$events = $github->activity->events->getRepository(':owner', ':repo');

// List issue events for a repository.
$events = $github->activity->events->getIssue(':owner', ':repo');

// List public events for a network of repositories.
$events = $github->activity->events->getNetwork(':owner', ':repo');

// List public events for an organization.
$events = $github->activity->events->getOrg(':org');

// List events that a user has received.
$events = $github->activity->events->getUser(':user');

// List public events that a user has received.
$events = $github->activity->events->getUserPublic(':user');

// List events performed by a user.
$events = $github->activity->events->getByUser(':user');

// List public events performed by a user.
$events = $github->activity->events->getByUserPublic(':user');

// List events for an organization
$events = $github->activity->events->getUserOrg(':user', ':org');
```

#### Notifications

See http://developer.github.com/v3/activity/notifications/.

```php
// List your notifications.
$all = true;
$participating = true;
$since = new Date('2012-12-12');
$notifications = $github->activity->notifications->getList($all, $participating, $since);

// List your notifications in a repository.
$notifications = $github->activity->notifications->getListRepository(':owner', ':repo', $all, $participating, $since);

// Mark as read.
$unread = true;
$read = true;
$lastReadAt = new Date('2012-12-12');
$github->activity->notifications->getListRepository($unread, $read, $lastReadAt);

// Mark notifications as read in a repository.
$github->activity->notifications->getListRepository(':owner', ':repo', $unread, $read, $lastReadAt);

// View a single thread.
$thread = $github->activity->notifications->viewThread(':id');

// Mark a thread as read.
$github->activity->notifications->markReadThread(':id', $unread, $read);

// Get a Thread Subscription.
$subscription = $github->activity->notifications->getThreadSubscription(':id');

// Set a Thread Subscription.
$subscribed = true;
$ignored = false;
$github->activity->notifications->setThreadSubscription(':id', $subscribed, $ignored);

// Delete a Thread Subscription.
$github->activity->notifications->deleteThreadSubscription(':id');
```

#### Starring

See http://developer.github.com/v3/activity/starring/.

```php
// List Stargazers.
$starred = $github->activity->starring->getList(':owner', ':repo');

// List repositories being starred.
$starred = $github->activity->starring->getRepositories(':user');

// Check if you are starring a repository.
// @return  boolean  True for a 204 response, False for a 404.
$isStarred = $github->activity->starring->check(':owner', ':repo');

// Star a repository.
$github->activity->starring->star(':owner', ':repo');

// Unstar a repository.
$github->activity->starring->unstar(':owner', ':repo');
```

#### Watching

See http://developer.github.com/v3/activity/watching/.

```php
// List watchers.
$watchers = $github->activity->watching->getList(':owner', ':repo');

// List repositories being watched.
$repos = $github->activity->watching->getRepositories(':user');

// Get a Repository Subscription.
$github->activity->watching->getSubscription(':owner', ':repo');

// Set a Repository Subscription.
$subscribed= true;
$ignored = false;
$subscription = $github->activity->watching->setSubscription(':owner', ':repo', $subscribed, $ignored);

// Delete a Repository Subscription.
$github->activity->watching->deleteSubscription(':owner', ':repo');

// Check if you are watching a repository (LEGACY).
// @return  boolean  True for a 204 response, False for a 404.
$github->activity->watching->check(':owner', ':repo');

// Watch a repository (LEGACY).
$github->activity->watching->watch(':owner', ':repo');

// Stop watching a repository (LEGACY).
$github->activity->watching->unwatch(':owner', ':repo');
```

### Gists

See http://developer.github.com/v3/gists/.

```php
// List gists.
$page = 0;
$limit = 20;
$gists = $github->gists->getList($page, $limit);

// List a user’s gists.
$gists = $github->gists->getListByUser(':user', $page, $limit);

// List all public gists
$gists = $github->gists->getListPublic($page, $limit);

// List the authenticated user’s starred gists.
$gists = $github->gists->getListStarred($page, $limit);

// Get a single gist.
$gist = $github->gists->get(':id');

// Create a gist.
$public = true;
$github->gists->create(array(':local-file-path'), $public, ':description');

// Edit a gist.
$github->gists->edit(':id', $files = array(':local-file-path'), $public, ':description');

// Star a gist.
$github->gists->star(':id');

// Unstar a gist.
$github->gists->unstar(':id');

// Check if a gist is starred.
// @return  boolean  True for a 204 response, False for a 404.
$github->gists->isStarred(':id');

// Fork a gist.
$github->gists->fork(':id');

// Delete a gist.
$github->gists->create(array(':id');
```

#### Comments

See http://developer.github.com/v3/gists/comments/.

```php
// List comments on a gist.
$comments = $github->gists->comments->getList(':gistId');

// Get a single comment.
$comment = $github->gists->comments->get(':commentId');

// Create a comment.
$github->gists->comments->create(':gistId', 'Comment');

// Edit a comment.
$github->gists->comments->edit(':commentId', 'Comment');

// Delete a comment.
$github->gists->comments->delete(':gistId');
```

### Git Data

See http://developer.github.com/v3/git/.

#### Blobs

See http://developer.github.com/v3/git/blobs/.

```php
// Get a Blob
$blob = $github->data->blobs->get(':owner', ':repo', ':sha');

// Create a Blob
$encoding = 'utf-8';
$github->data->blobs->create(':owner', ':repo', ':content', $encoding);
```

#### Commits

See http://developer.github.com/v3/git/commits/.

```php
// Get a Commit
$commit = $github->data->commits->get(':owner', ':repo', ':sha');

//Create a Commit
$parents = array(':sha');
$commit = $github->data->commits->get(':owner', ':repo', ':message', ':tree', $parents);
```

#### References

See http://developer.github.com/v3/git/refs/.

```php
// Get a Reference
$ref = $github->data->refs->get(':owner', ':repo', ':ref');

// Get all References
$namespace = ':namespace';
$page = 0;
$perPage = 20;
$refs = $github->data->refs->getList(':owner', ':repo', ':ref', $namespace, $page, $perPage);

// Create a Reference
$github->data->refs->create(':owner', ':repo', ':ref', ':sha');

// Update a Reference
$force = false;
$github->data->refs->edit(':owner', ':repo', ':ref', ':sha', $force);

// Delete a Reference
$github->data->refs->delete(':owner', ':repo', ':ref');
```

#### Tags

See http://developer.github.com/v3/git/tags/.

```php
// Get a Tag
$tag = $github->data->tags->get(':owner', ':repo', ':sha');

// Create a Tag Object
$github->data->tags->create(
    ':owner', ':repo', ':tag', ':message', ':object_sha', ':type', ':tagger_name', ':tagger_email', ':tagger_date'
);
```

#### Trees

See http://developer.github.com/v3/git/trees/.

```php
// Get a Tree
$tree = $github->data->trees->get(':owner', ':repo', ':sha');

// Get a Tree Recursively
$tree = $github->data->trees->getRecursively(':owner', ':repo', ':sha');

// Create a Tree
$tree = array(
    'path' => ':path',
    'mode' => ':mode',
    'type' => 'blob|tree|commit',
    'sha' => ':sha',
    'content' => ':content',
);
$github->data->trees->create(':owner', ':repo', $tree, ':base_tree');
```

### Issues

See http://developer.github.com/v3/issues/.

```php
// List issues
$filter = 'assigned|created|mentioned|subscribed';
$state = 'open|closed';
$labels = ':label1,:label2';
$sort = 'created|updated|comments';
$direction = 'asc|desc';
$since = new Date('2012-12-12');
$page = 0;
$perPage = 20;
$issues = $github->issues->getList($filter, $state, $labels, $sort, $direction, $since, $page, $perPage);

// List issues for a repository
$milestone = ':milestone|*|none';
$assignee = ':user|none';
$mentioned = ':user';
$issues = $github->issues->getListByRepository(
    ':owner', ':repo', $milestone, $state, $assignee, $mentioned, $labels, $sort, $direction, $since, $page, $perPage
);

// Get a single issue
$issue = $github->issues->get(':user', ':repo', ':issueId');

// Create an issue
$labels = array(':label');
$github->issues->create(':user', ':repo', ':title', ':body', ':assignee', ':milestone', $labels);

// Edit an issue
$github->issues->edit(':user', ':repo', ':issueId', 'open|closed', ':title', ':body', ':assignee', ':milestone', $labels);
```

#### Assignees

See http://developer.github.com/v3/issues/assignees/.

```php
// List assignees
$assignees = $github->issues->assignees->getList(':owner', ':repo');

// Check assignee
$isUserAssigned = $github->issues->assignees->check(':owner', ':repo', ':user');
```

#### Comments

See http://developer.github.com/v3/issues/comments/

```php
// List comments on an issue
$page = 0;
$perPage = 20;
$comments = $github->issues->comments->getList(':owner', ':repo', ':issueId', $page, $perPage);

// List comments in a repository
$sort = 'created|updated';
$direction = 'asc|desc';
$since = new Date('2012-12-12');
$comments = $github->issues->comments->getRepositoryList(':owner', ':repo', $sort, $direction, $since);

// Get a single comment
$comment = $github->issues->comments->get(':owner', ':repo', ':commentId');

// Create a comment
$github->issues->comments->get(':owner', ':repo', ':commentId');

// Edit a comment
$github->issues->comments->edit(':owner', ':repo', ':commentId', ':body');

// Delete a comment
$github->issues->comments->delete(':owner', ':repo', ':commentId');
```

#### Events

See http://developer.github.com/v3/issues/events/.

```php
// List events for an issue
$page = 0;
$perPage = 20;
$events = $github->issues->events->getList(':owner', ':repo', ':issueId', $page, $perPage);

// List events for a repository
$events = $github->issues->events->getListRepository(':owner', ':repo', ':issueId', $page, $perPage);

// Get a single event
$event = $github->issues->events->get(':owner', ':repo', ':issueId');
```

#### Labels

See http://developer.github.com/v3/issues/labels/.

```php
// List all labels for this repository
$labels = $github->issues->labels->getList(':owner', ':repo');

// Get a single label
$label = $github->issues->labels->get(':owner', ':repo', ':labelName');

// Create a label
$github->issues->labels->create(':owner', ':repo', ':labelName', ':labelColor');

// Update a label
$github->issues->labels->update(':owner', ':repo', ':oldLableName', ':newLabelName', ':labelColor');

// Delete a label
$github->issues->labels->delete(':owner', ':repo', ':labelName');

// List labels on an issue
$labels = $github->issues->labels->getListByIssue(':owner', ':repo', ':issueNumber');

// Add labels to an issue
$labels = array(':label1', ':label2');
$github->issues->labels->add(':owner', ':repo', ':issueNumber', $labels);

// Remove a label from an issue
$github->issues->labels->removeFromIssue(':owner', ':repo', ':issueNumber', ':labelName');

// Replace all labels for an issue
$github->issues->labels->replace(':owner', ':repo', ':issueNumber', $labels);

// Remove all labels from an issue
$github->issues->labels->removeAllFromIssue(':owner', ':repo', ':issueNumber');

// Get labels for every issue in a milestone
$labels = $github->issues->labels->getListByMilestone(':owner', ':repo', ':milestoneNumber');
```

#### Milestones

See http://developer.github.com/v3/issues/milestones/.

```php
// List milestones for a repository
$state = 'open|closed';
$sort = 'due_date|completeness';
$direction = 'asc|desc';
$page = 0;
$perPage = 20;
$milestones = $github->issues->milestones->getList(':owner', ':repo', $state, $sort, $direction, $page, $perPage);

// Get a single milestone
$milestone = $github->issues->milestones->get(':owner', ':repo', ':milestoneId');

// Create a milestone
$github->issues->milestones->create(':owner', ':repo', ':title', $state, ':description', ':due_on');

// Update a milestone
$github->issues->milestones->edit(':owner', ':repo', ':milestoneId', ':title', $state, ':description', ':due_on');

// Delete a milestone
$github->issues->milestones->delete(':owner', ':repo', ':milestoneId');
```

### Miscellaneous

See http://developer.github.com/v3/misc/.

#### Gitignore

See http://developer.github.com/v3/gitignore/

```php
// Listing available templates
$templates = $github->gitignore->getList();

// Get a single template
$raw = false;
$template = $github->gitignore->get(':name', $raw);
```

#### Markdown

See http://developer.github.com/v3/markdown/.

```php
// Render an arbitrary Markdown document
$mode = 'gfm|markdown';
$context = ':context';
$github->markdown->render(':text', $mode, $context);

// Render a Markdown document in raw mode
```

#### Meta

See http://developer.github.com/v3/meta/

```php
$meta = $github->getMeta();
```

#### Ratelimit

See http://developer.github.com/v3/rate_limit/.

```php
$rateLimit = $this->authorization->getRateLimit();
```

### Organisations

See http://developer.github.com/v3/orgs/.

```php
// List User Organizations
$orgs = $github->orgs->getList(':user');

// Get an Organization
$orgs = $github->orgs->get(':org');

// Edit an Organization
$github->orgs->edit(':org', ':billingEmail', ':company', ':email', ':location', ':name');
```

#### Members

See

```php
// Members list
$members = $github->orgs->members->getList(':org');

// Check membership
$isUserMember = $github->orgs->members->check(':org', ':user');

// Remove a member
$github->orgs->members->remove(':org', ':user');

// Public members list
$publicMembers = $github->orgs->members->getListPublic(':org');

// Check public membership
$isUserPublicMember = $github->orgs->members->checkPublic(':org', ':user');

// Publicize a user’s membership
$github->orgs->members->publicize(':org', ':user');

// Conceal a user’s membership
$github->orgs->members->conceal(':org', ':user');
```

#### Teams

See http://developer.github.com/v3/orgs/teams/.

```php
// List teams
$teams = $github->orgs->teams->getList(':org');

// Get team
$team = $github->orgs->teams->get(':teamId');

// Create team
$repos = array(':repoName');
$permission = 'pull|push|admin';
$github->orgs->teams->create(':org', ':name', $repos, $permission);

// Edit team
$github->orgs->teams->edit(':teamId', ':name', $permission);

// Delete team
$github->orgs->teams->delete(':teamId');

// List team members
$members = $github->orgs->teams->getListMembers(':teamId');

// Get team member
$isUserMember = $github->orgs->teams->isMember(':teamId', ':user');

// Add team member
$github->orgs->teams->addMember(':teamId', ':user');

// Remove team member
$github->orgs->teams->removeMember(':teamId', ':user');

// List team repos
$repos = $github->orgs->teams->getListRepos(':teamId');

// Get team repo
$isRepoManagedByTeam = $github->orgs->teams->checkRepo(':teamId', ':repo');

// Add team repo
$github->orgs->teams->addRepo(':teamId', ':org', ':repo');

// Remove team repo
$github->orgs->teams->removeRepo(':teamId', ':owner', ':repo');

// List user teams - TODO
```

### Pull Requests

See http://developer.github.com/v3/pulls/.

```php
// List pull requests
$state = 'open|closed';
$page = 0;
$perPage = 20;
$pulls = $github->pulls->getList(':owner', ':repo', $state, $page, $perPage);

// Get a single pull request
$pull = $github->pulls->get(':owner', ':repo', ':pullId');

// Create a pull request
$github->pulls->create(':owner', ':repo', ':title', ':branch|ref', ':head|ref', ':body');

// Create from issue
$github->pulls->createFromIssue(':owner', ':repo', ':issueId', ':branch|ref', ':head|ref');

// Update a pull request
$state = 'open|closed';
$github->pulls->edit(':owner', ':repo', ':pullId', ':title', ':body', $state);

// List commits on a pull request
$commits = $github->pulls->commits(':owner', ':repo', ':pullId', $page, $perPage);

// List pull requests files
$files = $github->pulls->commits(':owner', ':repo', ':pullId', $page, $perPage);

// Get if a pull request has been merged
$isMerged = $github->pulls->isMerged(':owner', ':repo', ':pullId');

// Merge a pull request
$isMerged = $github->pulls->merge(':owner', ':repo', ':pullId', ':message');
```

#### Review Comments

See http://developer.github.com/v3/pulls/comments/.

```php
// List comments on a pull request
$page = 0;
$perPage = 20;
$comments = $github->pulls->comments->get(':owner', ':repo', ':pullId', $page, $perPage);

// List comments in a repository - TODO

// Get a single comment
$comment = $github->pulls->comments->get(':owner', ':repo', ':commentId');

// Create a comment
$github->pulls->comments->create(':owner', ':repo', ':pullId', ':body', ':commitId', ':filePath', ':position');

// Create a comment
$github->pulls->comments->createReply(':owner', ':repo', ':pullId', ':body', ':replyingToCommentId');

// Edit a comment
$github->pulls->comments->edit(':owner', ':repo', ':commentId', ':body');

// Delete a comment
$github->pulls->comments->delete(':owner', ':repo', ':commentId');
```

### H3

See

```php
```

#### H4

See

```php
```

## TODO

* [Event Types](http://developer.github.com/v3/activity/events/types/)
* [Feeds](http://developer.github.com/v3/activity/feeds/)
* [Emojis](http://developer.github.com/v3/emojis/)
* [Render Raw Markdown](http://developer.github.com/v3/markdown/#render-a-markdown-document-in-raw-mode)
* [List user teams](http://developer.github.com/v3/orgs/teams/#list-user-teams)
* [List comments in a repo](http://developer.github.com/v3/pulls/comments/#list-comments-in-a-repository)

## See Also

The following resources contain more information:  [Joomla! API Reference](http://api.joomla.org),
[Github API Reference](http://developer.github.com).


## Installation via Composer

Add `"joomla/github": "dev-master"` to the require block in your composer.json, make sure you have
`"minimum-stability": "dev"` and then run `composer install`.

```json
{
	"require": {
		"joomla/github": "dev-master"
	},
	"minimum-stability": "dev"
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer init --stability="dev"
composer require joomla/github "dev-master"
```
