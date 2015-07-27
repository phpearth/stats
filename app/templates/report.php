Stats of the previous week at PHP Facebook Group on <?= $date ?>

http://wwphp-fb.github.io/contribute.html

○ Warm welcome to <?= $newUsersCount ?> new members

○ Thanks to top 10 most active members of the week:
<?php foreach ($topUsers as $topUser):?>
- <?= $topUser->getName() ?> (<?= $topUser->getPoints() ?> points)
<?php endforeach; ?>

○ <?= $newTopicsCount ?> new topics were created

○ <?= $bannedCount ?> users were banned for not following the group rules
wwphp-fb.github.io/rules.html

○ <?= $commitsCount ?> new commits to our GitHub repos:
git.io/vqLvG

○ <?= $jobsCount ?> new job opportunities:
fb.com/notes/php/jobs-administrated-document/10151718775065681

○ Most liked topic of the week:
fb.com/groups/2204685680/permalink/<?= $mostLikedTopicId ?>

○ Most active topic of the week:
fb.com/groups/2204685680/permalink/<?= $mostActiveTopicId ?>

○ Top topics (staff pick):


Your help is appreciated, get involved and contribute.