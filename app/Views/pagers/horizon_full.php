<?php
use CodeIgniter\Pager\PagerRenderer;

/** @var PagerRenderer $pager */
$pager->setSurroundCount(2);
?>
<nav aria-label="Page Navigation" class="flex items-center justify-between gap-2 px-6 py-4 border-t border-gray-100 dark:border-white/10 flex-wrap">
    <div class="hidden sm:block text-xs text-gray-500 dark:text-gray-400">
        Showing current page
    </div>

    <ul class="inline-flex items-center gap-1.5 text-xs font-medium">
        <?php if ($pager->hasPrevious()): ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-lightPrimary dark:bg-navy-700 text-gray-600 dark:text-gray-300 hover:bg-brand-50 hover:text-brand-500 dark:hover:bg-navy-600 transition-colors" title="First Page">
                    <i data-lucide="chevrons-left" class="w-4 h-4"></i>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="inline-flex items-center justify-center px-3 h-8 rounded-xl bg-lightPrimary dark:bg-navy-700 text-navy-700 dark:text-white font-bold hover:bg-brand-50 hover:text-brand-500 dark:hover:bg-navy-600 transition-colors" title="Previous">
                    <i data-lucide="chevron-left" class="w-4 h-4 mr-1"></i>
                    Prev
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-xl transition-all <?= $link['active'] ? 'bg-brand-500 text-white font-bold shadow-lg shadow-brand-500/25' : 'bg-lightPrimary dark:bg-navy-700 text-navy-700 dark:text-white hover:bg-brand-50 hover:text-brand-500 dark:hover:bg-navy-600' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()): ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="inline-flex items-center justify-center px-3 h-8 rounded-xl bg-lightPrimary dark:bg-navy-700 text-navy-700 dark:text-white font-bold hover:bg-brand-50 hover:text-brand-500 dark:hover:bg-navy-600 transition-colors" title="Next">
                    Next
                    <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-lightPrimary dark:bg-navy-700 text-gray-600 dark:text-gray-300 hover:bg-brand-50 hover:text-brand-500 dark:hover:bg-navy-600 transition-colors" title="Last Page">
                    <i data-lucide="chevrons-right" class="w-4 h-4"></i>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>
