

Hello, here are weekly group stats from <?= $startDate ?> to <?= $endDate ?>:
http://wwphp-fb.github.io/

○ Warm welcome to <?= $newUsersCount ?> new members

○ Thanks to top <?= $topUsersCount ?> members of the week:
<?php foreach ($topUsers as $topUser):?>
- <?= $topUser->getName() ?> (<?= $topUser->getPoints() ?> points, <?= $topUser->getTopicsCount() ?> topics, <?= $topUser->getCommentsCount()?> comments)
<?php endforeach; ?>

○ <?= $newTopicsCount ?> topics, <?= $newCommentsCount ?> comments and <?= $newRepliesCount ?> replies were created by <?= $activeUsersCount ?> active members this week.

○ <?= $bannedCount ?> users were banned for not following the group rules:
wwphp-fb.github.io/rules.html

○ <?= $commitsCount ?> new commits to our GitHub repos:
git.io/vqLvG

○ Most liked topic of the week with <?= $mostLikesCount ?> likes:
fb.com/groups/2204685680/permalink/<?= $mostLikedTopicId ?>


○ Most active topic of the week with <?= $mostCommentsCount ?> comments:
fb.com/groups/2204685680/permalink/<?= $mostActiveTopicId ?>


○ PHP related topics you should check out (staff pick):
<?php foreach ($topTopics as $topic):?>
- <?= $topic['title'] ?>:
<?= $topic['url'] ?>

<?php endforeach; ?>


Your help is appreciated, get involved and contribute.
http://wwphp-fb.github.io/contribute.html