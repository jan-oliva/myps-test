<?php

namespace TestModule;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends ABasePresenter
{


	public function renderDefault()
	{
		$this->template->allowUsersLink = $this->getUSer()->isAllowed('Test:Users:Users','list');
		$this->template->isLoggedIn = $this->getUser()->isLoggedIn();
	}
}
