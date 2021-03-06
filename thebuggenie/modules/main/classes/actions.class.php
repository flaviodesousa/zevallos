<?php 

	/**
	 * actions for the main module
	 */
	class mainActions extends TBGAction
	{

		/**
		 * The currently selected project in actions where there is one
		 *
		 * @access protected
		 * @property TBGProject $selected_project
		 */

		public function preExecute(TBGRequest $request, $action)
		{
			try
			{
				if ($project_key = $request->getParameter('project_key'))
					$this->selected_project = TBGProject::getByKey($project_key);
				elseif ($project_id = (int) $request->getParameter('project_id'))
					$this->selected_project = TBGContext::factory()->TBGProject($project_id);
				
				TBGContext::setCurrentProject($this->selected_project);
			}
			catch (Exception $e) {}
		}
		
		/**
		 * View an issue
		 * 
		 * @param TBGRequest $request
		 */
		public function runViewIssue(TBGRequest $request)
		{
			//TBGEvent::listen('core', 'viewissue', array($this, 'listenViewIssuePostError'));
			TBGLogging::log('Loading issue');
			
			if ($issue_no = TBGContext::getRequest()->getParameter('issue_no'))
			{
				$issue = TBGIssue::getIssueFromLink($issue_no);
				if ($issue instanceof TBGIssue)
				{
					if (!$this->selected_project instanceof TBGProject || $issue->getProjectID() != $this->selected_project->getID())
					{
						$issue = null;
					}
				}
				else
				{
					TBGLogging::log("Issue no [$issue_no] not a valid issue no", 'main', TBGLogging::LEVEL_WARNING_RISK);
				}
			}
			TBGLogging::log('done (Loading issue)');
			//$this->getResponse()->setPage('viewissue');
			if ($issue instanceof TBGIssue && (!$issue->hasAccess() || $issue->isDeleted()))
			{
				$issue = null;
			}
			$message = TBGContext::getMessageAndClear('issue_saved');
			$uploaded = TBGContext::getMessageAndClear('issue_file_uploaded');
			
			if ($request->isMethod(TBGRequest::POST) && $issue instanceof TBGIssue && $request->hasParameter('issue_action'))
			{
				switch ($request->getParameter('issue_action'))
				{
					case 'save':
						if ($issue->hasUnsavedChanges())
						{
							if (!$issue->hasMergeErrors())
							{
								try
								{
									$issue->getWorkflowStep()->getWorkflow()->moveIssueToMatchingWorkflowStep($issue);
									$issue->save();
									TBGContext::setMessage('issue_saved', true);
									$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
								}
								catch (TBGWorkflowException $e)
								{
									$this->error = $e->getMessage();
									$this->workflow_error = true;
								}
								catch (Exception $e)
								{
									$this->error = $e->getMessage();
								}
							}
							else
							{
								$this->issue_unsaved = true;
							}
						}
						else
						{
							$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
						}
						break;
				}
			}
			elseif ($message == true)
			{
				$this->issue_saved = true;
			}
			elseif ($uploaded == true)
			{
				$this->issue_file_uploaded = true;
			}
			elseif (TBGContext::hasMessage('issue_error'))
			{
				$this->error = TBGContext::getMessageAndClear('issue_error');
			}
			$this->issue = $issue;
			$event = TBGEvent::createNew('core', 'viewissue', $issue)->trigger();
			$this->listenViewIssuePostError($event);
		}
		
		/**
		 * Frontpage
		 *  
		 * @param TBGRequest $request
		 */
		public function runIndex(TBGRequest $request)
		{
			if (TBGSettings::isSingleProjectTracker())
			{
				if (($projects = TBGProject::getAll()) && $project = array_shift($projects))
				{
					$this->forward(TBGContext::getRouting()->generate('project_dashboard', array('project_key' => $project->getKey())));
				}
			}
			$this->forward403unless(TBGContext::getUser()->hasPageAccess('home'));
			$this->getResponse()->setProjectMenuStripHidden();
			$this->links = TBGContext::getMainLinks();
		}

		/**
		 * Developer dashboard
		 *  
		 * @param TBGRequest $request
		 */
		public function runDashboard(TBGRequest $request)
		{
			$this->forward403unless(!TBGContext::getUser()->isThisGuest() && TBGContext::getUser()->hasPageAccess('dashboard'));
			if (!TBGSettings::isSingleProjectTracker())
			{
				$this->getResponse()->setProjectMenuStripHidden();
			}
			else
			{
				if (($projects = TBGProject::getAll()) && $project = array_shift($projects))
				{
					$this->getResponse()->setProjectMenuStripHidden(false);
					TBGContext::setCurrentProject($project);
				}
			}
			$this->dashboardViews = TBGDashboard::getUserViews();
		}
		
		/**
		 * Save dashboard configuration (AJAX call)
		 *  
		 * @param TBGRequest $request
		 */
		public function runDashboardSave(TBGRequest $request)
		{
			$i18n = TBGContext::getI18n();
			$this->login_referer = (array_key_exists('HTTP_REFERER', $_SERVER) && isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
			$this->options = $request->getParameters();
			try
			{
				if (TBGContext::getRequest()->isAjaxCall() || TBGContext::getRequest()->getRequestedFormat() == 'json')
				{
					if ($request->getMethod() == TBGRequest::POST)
					{
						if ($request->hasParameter('id'))
						{
							$views = array();
							foreach(explode(';', $request->getParameter('id')) as $view)
							{
								array_push($views, array('type' => strrev(strstr(strrev($view), '_', true)), 'id' => strstr($view, '_', true)));
							}
							array_pop($views);
							TBGDashboard::setUserViews(TBGContext::getUser()->getID(), $views);
							return $this->renderJSON(array('message' => $i18n->__('Dashboard configuration saved')));
						}
						else
						{
							throw new Exception($i18n->__('An internal error has occured'));
						}
					}
					else 
					{
						throw new Exception($i18n->__('An internal error has occured'));
					}
				}
				else 
				{
					throw new Exception($i18n->__('An internal error has occured'));
				}				
			}
			catch (Exception $e)
			{
				return $this->renderJSON(array('failed' => true, 'error' => $i18n->__($e->getMessage()), 'referer' => $request->getParameter('tbg3_referer')));
			}
		}
		
		/**
		 * Client Dashboard
		 *  
		 * @param TBGRequest $request
		 */
		public function runClientDashboard(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPageAccess('home'));
			$this->getResponse()->setProjectMenuStripHidden();
			$this->client = null;
			try
			{
				$this->client = TBGContext::factory()->TBGClient($request->getParameter('client_id'));
			}
			catch (Exception $e)
			{
				return $this->return404(TBGContext::getI18n()->__('This client does not exist'));
				TBGLogging::log($e->getMessage(), 'core', TBGLogging::LEVEL_WARNING);
			}
		}
		
		/**
		 * Team Dashboard
		 *  
		 * @param TBGRequest $request
		 */
		public function runTeamDashboard(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPageAccess('home'));
			$this->getResponse()->setProjectMenuStripHidden();

			try
			{
				$this->team = TBGContext::factory()->TBGTeam($request->getParameter('team_id'));
				
				$own = TBGProject::getAllByOwner($this->team);
				$leader = TBGProject::getAllByLeader($this->team);
				$qa = TBGProject::getAllByQaResponsible($this->team);
				$proj = $this->team->getAssociatedProjects();
				
				$this->projects = array_unique(array_merge($proj, $own, $leader, $qa));
				$this->users = $this->team->getMembers();
			}
			catch (Exception $e)
			{
				return $this->return404(TBGContext::getI18n()->__('This client does not exist'));
				TBGLogging::log($e->getMessage(), 'core', TBGLogging::LEVEL_WARNING);
			}
		}
				
		/**
		 * About page
		 *  
		 * @param TBGRequest $request
		 */
		public function runAbout(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPageAccess('about'));
			$this->getResponse()->setProjectMenuStripHidden();
		}
		
		/**
		 * 404 not found page
		 * 
		 * @param TBGRequest $request
		 */
		public function runNotFound(TBGRequest $request)
		{
			$this->getResponse()->setHttpStatus(404);
			$message = null;
		}
		
		/**
		 * Logs the user out
		 * 
		 * @param TBGRequest $request
		 */
		public function runLogout(TBGRequest $request)
		{
			if (TBGContext::getUser() instanceof TBGUser)
			{
				TBGLogging::log('Setting user logout state');
				TBGContext::getUser()->setOffline();
			}
			TBGContext::logout();
			$this->forward(TBGContext::getRouting()->generate(TBGSettings::getLogoutReturnRoute()));
		}
		
		/**
		 * Login (AJAX call)
		 *  
		 * @param TBGRequest $request
		 */
		public function runLogin(TBGRequest $request)
		{
			$i18n = TBGContext::getI18n();
			$this->login_referer = (array_key_exists('HTTP_REFERER', $_SERVER) && isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
			$options = $request->getParameters();
			$forward_url = TBGContext::getRouting()->generate('home');
			try
			{
				if ($request->getMethod() == TBGRequest::POST)
				{
					if ($request->hasParameter('tbg3_username') && $request->hasParameter('tbg3_password'))
					{
						$username = $request->getParameter('tbg3_username');
						$password = $request->getParameter('tbg3_password');
						$user = TBGUser::loginCheck($username, $password, true);
						$this->getResponse()->setCookie('tbg3_username', $username);
						$this->getResponse()->setCookie('tbg3_password', TBGUser::hashPassword($password));
						TBGContext::setUser($user);
						if ($request->hasParameter('return_to')) 
						{
							$forward_url = $request->getParameter('return_to');
						}
						else
						{
							if (TBGSettings::get('returnfromlogin') == 'referer')
							{
								if ($request->getParameter('tbg3_referer'))
								{
									$forward_url = $request->getParameter('tbg3_referer');
								}
								else
								{
									$forward_url = TBGContext::getRouting()->generate('dashboard');
								}
							}
							else
							{
								$forward_url = TBGContext::getRouting()->generate(TBGSettings::get('returnfromlogin'));
							}
						}
					}
					else
					{
						throw new Exception($i18n->__('Please enter a username and password'));
					}
				}
				elseif (TBGSettings::isLoginRequired())
				{
					throw new Exception($i18n->__('You need to log in to access this site'));
				}
				elseif (!TBGContext::getUser()->isAuthenticated())
				{
					throw new Exception($i18n->__('Please log in'));
				}
				elseif (TBGContext::hasMessage('forward'))
				{
					throw new Exception($i18n->__(TBGContext::getMessageAndClear('forward')));
				}
			}
			catch (Exception $e)
			{
				if (TBGContext::getRequest()->isAjaxCall() || TBGContext::getRequest()->getRequestedFormat() == 'json')
				{
					return $this->renderJSON(array('failed' => true, "error" => $i18n->__($e->getMessage()), 'referer' => $request->getParameter('tbg3_referer')));
				}
				else
				{
					$options['error'] = $e->getMessage();
				}
			}
			if (TBGContext::getRequest()->isAjaxCall() || TBGContext::getRequest()->getRequestedFormat() == 'json')
			{
				return $this->renderJSON(array('forward' => $forward_url));
			}

			elseif ($forward_url !== null && $request->getParameter('continue') != true)
			{
				$this->forward($forward_url);
			}
			$this->options = $options;
		}
		
		/**
		 * Registration logic part 1 - check if username is free (AJAX call)
		 * 
		 * @param TBGRequest $request The request object
		 */
		public function runRegister1(TBGRequest $request)
		{
			$i18n = TBGContext::getI18n();

			try
			{
				$username = $request->getParameter('desired_username');
				if (!empty($username))
				{
					$exists = TBGUsersTable::getTable()->getByUsername($username);
					
					if ($exists)
					{
						throw new Exception($i18n->__('This username is in use'));
					}
					else
					{
						return $this->renderJSON(array('message' => $username));
					}
				}
				else
				{
					throw new Exception($i18n->__('Please enter a username'));
				}
			}
			catch (Exception $e)
			{
				return $this->renderJSON(array('failed' => true, 'error' => $i18n->__($e->getMessage())));
			}
		}		

		/**
		 * Registration logic part 2 - add user data
		 *  
		 * @param TBGRequest $request
		 */
		public function runRegister2(TBGRequest $request)
		{
			TBGContext::loadLibrary('common');
			$i18n = TBGContext::getI18n();
			
			try
			{
				$username = $request->getParameter('username');
				$buddyname = $request->getParameter('buddyname');
				$email = $request->getParameter('email_address');
				$confirmemail = $request->getParameter('email_confirm');
				$security = $request->getParameter('verification_no');
				$realname = $request->getParameter('realname');
				
				$fields = array();
				
				if (!empty($buddyname) && !empty($email) && !empty($confirmemail) && !empty($security))
				{
					if ($email != $confirmemail)
					{
						array_push($fields, 'email_address', 'email_confirm');
						throw new Exception($i18n->__('The email address must be valid, and must be typed twice.'));
					}

					if ($security != $_SESSION['activation_number'])
					{
						array_push($fields, 'verification_no');
						throw new Exception($i18n->__('To prevent automatic sign-ups, enter the verification number shown below.'));
					}

					$email_ok = false;
					$valid_domain = false;

					if (tbg_check_syntax($email, "EMAIL"))
					{
						$email_ok = true;
					}
					
					if ($email_ok && TBGSettings::get('limit_registration') != '')
					{

						$allowed_domains = preg_replace('/[[:space:]]*,[[:space:]]*/' ,'|', TBGSettings::get('limit_registration'));					
						if (preg_match('/@(' . $allowed_domains . ')$/i', $email) == false)
						{							
							array_push($fields, 'email_address', 'email_confirm');					
							throw new Exception($i18n->__('Email adresses from this domain can not be used.'));
						}
						/*if (count($allowed_domains) > 0)
						{
							foreach ($allowed_domains as $allowed_domain)
							{
								$allowed_domain = '@' . trim($allowed_domain);
								if (strpos($email, $allowed_domain) !== false ) //strpos checks if $to
								{
									$valid_domain = true;
									break;
								}
							}
							
						}
						else
						{
							$valid_domain = true;
						}*/
					}
					/*if ($valid_domain == false)
					{
						array_push($fields, 'email_address', 'email_confirm');					
						throw new Exception($i18n->__('Email adresses from this domain can not be used.'));
					}*/
					
					if($email_ok == false)
					{
						array_push($fields, 'email_address', 'email_confirm');
						throw new Exception($i18n->__('The email address must be valid, and must be typed twice.'));
					}
					
					if ($security != $_SESSION['activation_number'])
					{
						array_push($fields, 'verification_no');
						throw new Exception($i18n->__('To prevent automatic sign-ups, enter the verification number shown below.'));
					}					

					$password = TBGUser::createPassword();
					$user = new TBGUser();
					$user->setUsername($username);
					$user->setRealname($realname);
					$user->setBuddyname($buddyname);
					$user->setGroup(TBGSettings::getDefaultGroup());
					$user->setEnabled();
					$user->setPassword($password);
					$user->setEmail($email);
					$user->save();

					if ($user->isActivated())
					{
						return $this->renderJSON(array('message' => $i18n->__('A password has been autogenerated for you. To log in, use the following password:') . ' <b>' . $password . '</b>'));
					}
					return $this->renderJSON(array('message' => $i18n->__('The account has now been registered - check your email inbox for the activation email. Please be patient - this email can take up to two hours to arrive.')));
				}
				else
				{
					array_push($fields, 'email_address', 'email_confirm', 'buddyname', 'verification_no');
					throw new Exception($i18n->__('You need to fill out all fields correctly.'));
				}
			}
			catch (Exception $e)
			{
				return $this->renderJSON(array('failed' => true, 'error' => $i18n->__($e->getMessage()), 'fields' => $fields));
			}
		}

		/**
		 * Activate newly registered account
		 *  
		 * @param TBGRequest $request
		 */
		public function runActivate(TBGRequest $request)
		{
			$this->getResponse()->setPage('login');
			
			$row = TBGUsersTable::getTable()->getByUsername($request->getParameter('user'));
			if ($row)
			{
				if ($row->get(TBGUsersTable::PASSWORD) != $request->getParameter('key'))
				{
					TBGContext::setMessage('account_activate', true);
					TBGContext::setMessage('activate_failure', true);
				}
				else
				{
					$user = new TBGUser($row->get(TBGUsersTable::ID), $row);
					$user->setValidated(true);
					$user->save();
					TBGContext::setMessage('account_activate', true);
					TBGContext::setMessage('activate_success', true);
				}
			}
			else
			{
				TBGContext::setMessage('account_activate', true);
				TBGContext::setMessage('activate_failure', true);
			}
			$this->forward(TBGContext::getRouting()->generate('login'));
		}

		/**
		 * "My account" page
		 *  
		 * @param TBGRequest $request
		 */
		public function runMyAccount(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPageAccess('account'));
			if ($request->isMethod(TBGRequest::POST) && $request->hasParameter('mode'))
			{
				switch ($request->getParameter('mode'))
				{
					case 'information':
						if (!$request->getParameter('buddyname') || !$request->getParameter('email'))
						{
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Please fill out all the required fields')));
						}
						TBGContext::getUser()->setBuddyname($request->getParameter('buddyname'));
						TBGContext::getUser()->setRealname($request->getParameter('realname'));
						TBGContext::getUser()->setHomepage($request->getParameter('homepage'));
						TBGContext::getUser()->setEmailPrivate((bool) $request->getParameter('email_private'));

						if (TBGContext::getUser()->getEmail() != $request->getParameter('email'))
						{
							if (TBGEvent::createNew('core', 'changeEmail', TBGContext::getUser(), array('email' => $request->getParameter('email')))->triggerUntilProcessed()->isProcessed() == false)
							{
								TBGContext::getUser()->setEmail($request->getParameter('email'));
							}
						}

						TBGContext::getUser()->save();

						return $this->renderJSON(array('failed' => false, 'title' => TBGContext::getI18n()->__('Account information saved'), 'content' => ''));
						break;
					case 'settings':
						TBGContext::getUser()->setUsesGravatar((bool) $request->getParameter('use_gravatar'));
						TBGContext::getUser()->setTimezone($request->getParameter('timezone'));
						TBGContext::getUser()->save();

						return $this->renderJSON(array('failed' => false, 'title' => TBGContext::getI18n()->__('Profile settings saved'), 'content' => ''));
						break;
					case 'module':
						foreach (TBGContext::getModules() as $module_name => $module)
						{
							if ($request->getParameter('target_module') == $module_name && $module->hasAccountSettings())
							{
								if ($module->postAccountSettings($request))
								{
									return $this->renderJSON(array('failed' => false, 'title' => TBGContext::getI18n()->__('Settings saved'), 'content' => ''));
								}
								else
								{
									return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('An error occured')));
								}
							}
						}
						break;
				}
			}
			$this->rnd_no = rand();
			$this->getResponse()->setPage('account');
			$this->getResponse()->setProjectMenuStripHidden();
		}

		/**
		 * Change password ajax action
		 *
		 * @param TBGRequest $request
		 */
		public function runAccountChangePassword(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPageAccess('account'));
			if ($request->isMethod(TBGRequest::POST))
			{
				if (TBGContext::getUser()->canChangePassword() == false)
				{
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__("You're not allowed to change your password.")));
				}
				if (!$request->hasParameter('current_password') || !$request->getParameter('current_password'))
				{
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Please enter your current password')));
				}
				if (!$request->hasParameter('new_password_1') || !$request->getParameter('new_password_1'))
				{
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Please enter a new password')));
				}
				if (!$request->hasParameter('new_password_2') || !$request->getParameter('new_password_2'))
				{
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Please enter the new password twice')));
				}
				if (!TBGContext::getUser()->hasPassword($request->getParameter('current_password')))
				{
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Please enter your current password')));
				}
				if ($request->getParameter('new_password_1') != $request->getParameter('new_password_2'))
				{
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Please enter the new password twice')));
				}
				TBGContext::getUser()->changePassword($request->getParameter('new_password_1'));
				TBGContext::getUser()->save();
				$this->getResponse()->setCookie('tbg3_password', TBGContext::getUser()->getHashPassword());
				return $this->renderJSON(array('failed' => false, 'title' => TBGContext::getI18n()->__('Your new password has been saved')));
			}
		}

		protected function _setupReportIssueProperties()
		{
			$this->selected_project = null;
			$this->selected_issuetype = null;
			$this->selected_edition = null;
			$this->selected_build = null;
			$this->selected_component = null;
			$this->selected_category = null;
			$this->selected_status = null;
			$this->selected_resolution = null;
			$this->selected_priority = null;
			$this->selected_reproducability = null;
			$this->selected_severity = null;
			$this->selected_estimated_time = null;
			$this->selected_spent_time = null;
			$this->selected_percent_complete = null;
			$this->selected_pain_bug_type = null;
			$this->selected_pain_likelihood = null;
			$this->selected_pain_effect = null;
			$selected_customdatatype = array();
			foreach (TBGCustomDatatype::getAll() as $customdatatype)
			{
				$selected_customdatatype[$customdatatype->getKey()] = null;
			}
			$this->selected_customdatatype = $selected_customdatatype;
			$this->issuetypes = array();
			$this->issuetype_id = null;
			$this->issue = null;
			$this->categories = TBGCategory::getAll();
			$this->severities = TBGSeverity::getAll();
			$this->priorities = TBGPriority::getAll();
			$this->reproducabilities = TBGReproducability::getAll();
			$this->resolutions = TBGResolution::getAll();
			$this->statuses = TBGStatus::getAll();
			$this->projects = TBGProject::getAll();
		}

		protected function _clearReportIssueProperties()
		{
			$this->title = null;
			$this->description = null;
			$this->reproduction_steps = null;
			$this->selected_category = null;
			$this->selected_status = null;
			$this->selected_reproducability = null;
			$this->selected_resolution = null;
			$this->selected_severity = null;
			$this->selected_priority = null;
			$this->selected_edition = null;
			$this->selected_build = null;
			$this->selected_component = null;
			$this->selected_estimated_time = null;
			$this->selected_spent_time = null;
			$this->selected_percent_complete = null;
			$this->selected_pain_bug_type = null;
			$this->selected_pain_likelihood = null;
			$this->selected_pain_effect = null;
			$selected_customdatatype = array();
			foreach (TBGCustomDatatype::getAll() as $customdatatype)
			{
				$selected_customdatatype[$customdatatype->getKey()] = null;
			}
			$this->selected_customdatatype = $selected_customdatatype;
		}

		protected function _loadSelectedProjectAndIssueTypeFromRequestForReportIssueAction(TBGRequest $request)
		{
			if ($project_key = $request->getParameter('project_key'))
			{
				try
				{
					$this->selected_project = TBGProject::getByKey($project_key);
				}
				catch (Exception $e) {}
			}
			elseif ($project_id = $request->getParameter('project_id'))
			{
				try
				{
					$this->selected_project = TBGContext::factory()->TBGProject($project_id);
				}
				catch (Exception $e) {}
			}
			if ($this->selected_project instanceof TBGProject)
			{
				TBGContext::setCurrentProject($this->selected_project);
			}
			if ($this->selected_project instanceof TBGProject)
			{
				$this->issuetypes = $this->selected_project->getIssuetypeScheme()->getIssuetypes();
			}
			else
			{
				$this->issuetypes = TBGIssuetype::getAll();
			}

			if ($request->hasParameter('issuetype'))
			{
				$this->selected_issuetype = TBGIssuetype::getIssuetypeByKeyish($request->getParameter('issuetype'));
			}
			if (!$this->selected_issuetype instanceof TBGIssuetype)
			{
				$this->issuetype_id = $request->getParameter('issuetype_id');
				if ($this->issuetype_id)
				{
					try
					{
						$this->selected_issuetype = TBGContext::factory()->TBGIssuetype($this->issuetype_id);
					}
					catch (Exception $e) {}
				}
			}
			else
			{
				$this->issuetype_id = $this->selected_issuetype->getID();
			}
		}

		protected function _postIssueValidation(TBGRequest $request, &$errors, &$permission_errors)
		{
			$i18n = TBGContext::getI18n();
			if (!$this->selected_project instanceof TBGProject) $errors['project'] = $i18n->__('You have to select a valid project');
			if (!$this->selected_issuetype instanceof TBGIssuetype) $errors['issuetype'] = $i18n->__('You have to select a valid issue type');
			if (empty($errors))
			{
				$fields_array = $this->selected_project->getReportableFieldsArray($this->issuetype_id);

				$this->title = $request->getRawParameter('title');
				$this->selected_description = $request->getRawParameter('description', null, false);
				$this->selected_reproduction_steps = $request->getRawParameter('reproduction_steps', null, false);

				if ($edition_id = (int) $request->getParameter('edition_id'))
				{
					$this->selected_edition = TBGContext::factory()->TBGEdition($edition_id);
				}
				if ($build_id = (int) $request->getParameter('build_id'))
				{
					$this->selected_build = TBGContext::factory()->TBGBuild($build_id);
				}
				if ($component_id = (int) $request->getParameter('component_id'))
				{
					$this->selected_component = TBGContext::factory()->TBGComponent($component_id);
				}

				if (trim($this->title) == '' || $this->title == $this->default_title) $errors['title'] = true;
				if (isset($fields_array['description']) && $fields_array['description']['required'] && trim($this->selected_description) == '') $errors['description'] = true;
				if (isset($fields_array['reproduction_steps']) && $fields_array['reproduction_steps']['required'] && trim($this->selected_reproduction_steps) == '') $errors['reproduction_steps'] = true;

				if (isset($fields_array['edition']))
				{
					if ($edition_id && !in_array($edition_id, array_keys($fields_array['edition']['values'])))
						$errors['edition'] = true; // $i18n->__('The edition you specified is invalid');
				}

				if (isset($fields_array['build']))
				{
					if ($build_id && !in_array($build_id, array_keys($fields_array['build']['values'])))
						$errors['build'] = true; //$i18n->__('The release you specified is invalid');
				}

				if (isset($fields_array['component']))
				{
					if ($component_id && !in_array($component_id, array_keys($fields_array['component']['values'])))
						$errors['component'] = true; //$i18n->__('The component you specified is invalid');
				}

				if ($category_id = (int) $request->getParameter('category_id'))
				{
					$this->selected_category = TBGContext::factory()->TBGCategory($category_id);
				}

				if ($status_id = (int) $request->getParameter('status_id'))
				{
					$this->selected_status = TBGContext::factory()->TBGStatus($status_id);
				}

				if ($reproducability_id = (int) $request->getParameter('reproducability_id'))
				{
					$this->selected_reproducability = TBGContext::factory()->TBGReproducability($reproducability_id);
				}

				if ($resolution_id = (int) $request->getParameter('resolution_id'))
				{
					$this->selected_resolution = TBGContext::factory()->TBGResolution($resolution_id);
				}

				if ($severity_id = (int) $request->getParameter('severity_id'))
				{
					$this->selected_severity = TBGContext::factory()->TBGSeverity($severity_id);
				}

				if ($priority_id = (int) $request->getParameter('priority_id'))
				{
					$this->selected_priority = TBGContext::factory()->TBGPriority($priority_id);
				}

				if ($request->getParameter('estimated_time'))
				{
					$this->selected_estimated_time = $request->getParameter('estimated_time');
				}

				if ($request->getParameter('spent_time'))
				{
					$this->selected_spent_time = $request->getParameter('spent_time');
				}

				if (is_numeric($request->getParameter('percent_complete')))
				{
					$this->selected_percent_complete = (int) $request->getParameter('percent_complete');
				}

				if ($pain_bug_type_id = (int) $request->getParameter('pain_bug_type_id'))
				{
					$this->selected_pain_bug_type = $pain_bug_type_id;
				}

				if ($pain_likelihood_id = (int) $request->getParameter('pain_likelihood_id'))
				{
					$this->selected_pain_likelihood = $pain_likelihood_id;
				}

				if ($pain_effect_id = (int) $request->getParameter('pain_effect_id'))
				{
					$this->selected_pain_effect = $pain_effect_id;
				}

				$selected_customdatatype = array();
				foreach (TBGCustomDatatype::getAll() as $customdatatype)
				{
					if ($customdatatype->hasCustomOptions())
					{
						$selected_customdatatype[$customdatatype->getKey()] = null;
						$customdatatype_id = $customdatatype->getKey() . '_id';
						if ($request->hasParameter($customdatatype_id))
						{
							$$customdatatype_id = $request->getParameter($customdatatype_id);
							$selected_customdatatype[$customdatatype->getKey()] = TBGCustomDatatypeOption::getByValueAndKey($$customdatatype_id, $customdatatype->getKey());
						}
					}
					else
					{
						$selected_customdatatype[$customdatatype->getKey()] = null;
						$customdatatype_value = $customdatatype->getKey() . '_value';
						switch ($customdatatype->getType())
						{
							case TBGCustomDatatype::INPUT_TEXTAREA_MAIN:
							case TBGCustomDatatype::INPUT_TEXTAREA_SMALL:
								if ($request->hasParameter($customdatatype_value))
								{
									$selected_customdatatype[$customdatatype->getKey()] = $request->getParameter($customdatatype_value, null, false);
								}
								break;
							default:
								if ($request->hasParameter($customdatatype_value))
								{
									$selected_customdatatype[$customdatatype->getKey()] = $request->getParameter($customdatatype_value);
								}
								else
								{
									$customdatatype_id = $customdatatype->getKey() . '_id';
									if ($request->hasParameter($customdatatype_id))
									{
										$selected_customdatatype[$customdatatype->getKey()] = $request->getParameter($customdatatype_id);
									}
								}
								break;
						}
					}
				}
				$this->selected_customdatatype = $selected_customdatatype;

				foreach ($fields_array as $field => $info)
				{
					if ($field == 'user_pain')
					{
						if ($info['required'])
						{
							if (!($this->selected_pain_bug_type != 0 && $this->selected_pain_likelihood != 0 && $this->selected_pain_effect != 0))
							{
								$errors['user_pain'] = true;
							}
						}
					}
					elseif ($info['required'])
					{
						$var_name = "selected_{$field}";
						if ((in_array($field, TBGDatatype::getAvailableFields(true)) && ($this->$var_name === null || $this->$var_name === 0)) || (!in_array($field, TBGDatatype::getAvailableFields(true)) && !in_array($field, array('pain_bug_type', 'pain_likelihood', 'pain_effect')) && (array_key_exists($field, $selected_customdatatype) && $selected_customdatatype[$field] === null)))
						{
							$errors[$field] = true;
						}
					}
					else
					{
						if (in_array($field, TBGDatatype::getAvailableFields(true)))
						{
							if (!$this->selected_project->fieldPermissionCheck($field, true))
							{
								$permission_errors[$field] = true;
							}
						}
						elseif (!$this->selected_project->fieldPermissionCheck($field, true, true))
						{
							$permission_errors[$field] = true;
						}
					}
				}

			}
			return !(bool) (count($errors) + count($permission_errors));
		}

		protected function _postIssue()
		{
			$fields_array = $this->selected_project->getReportableFieldsArray($this->issuetype_id);
			$issue = new TBGIssue();
			$issue->setTitle($this->title);
			$issue->setIssuetype($this->issuetype_id);
			$issue->setProject($this->selected_project);
			if (isset($fields_array['description'])) $issue->setDescription($this->selected_description);
			if (isset($fields_array['reproduction_steps'])) $issue->setReproductionSteps($this->selected_reproduction_steps);
			if (isset($fields_array['category']) && $this->selected_category instanceof TBGDatatype) $issue->setCategory($this->selected_category->getID());
			if (isset($fields_array['status']) && $this->selected_status instanceof TBGDatatype) $issue->setStatus($this->selected_status->getID());
			if (isset($fields_array['reproducability']) && $this->selected_reproducability instanceof TBGDatatype) $issue->setReproducability($this->selected_reproducability->getID());
			if (isset($fields_array['resolution']) && $this->selected_resolution instanceof TBGDatatype) $issue->setResolution($this->selected_resolution->getID());
			if (isset($fields_array['severity']) && $this->selected_severity instanceof TBGDatatype) $issue->setSeverity($this->selected_severity->getID());
			if (isset($fields_array['priority']) && $this->selected_priority instanceof TBGDatatype) $issue->setPriority($this->selected_priority->getID());
			if (isset($fields_array['estimated_time'])) $issue->setEstimatedTime($this->selected_estimated_time);
			if (isset($fields_array['spent_time'])) $issue->setSpentTime($this->selected_spent_time);
			if (isset($fields_array['percent_complete'])) $issue->setPercentCompleted($this->selected_percent_complete);
			if (isset($fields_array['pain_bug_type'])) $issue->setPainBugType($this->selected_pain_bug_type);
			if (isset($fields_array['pain_likelihood'])) $issue->setPainLikelihood($this->selected_pain_likelihood);
			if (isset($fields_array['pain_effect'])) $issue->setPainEffect($this->selected_pain_effect);
			foreach (TBGCustomDatatype::getAll() as $customdatatype)
			{
				if (!isset($fields_array[$customdatatype->getKey()])) continue;
				if ($customdatatype->hasCustomOptions())
				{
					if (isset($fields_array[$customdatatype->getKey()]) && $this->selected_customdatatype[$customdatatype->getKey()] instanceof TBGCustomDatatypeOption)
					{
						$selected_option = $this->selected_customdatatype[$customdatatype->getKey()];
						$issue->setCustomField($customdatatype->getKey(), $selected_option->getValue());
					}
				}
				else
				{
					$issue->setCustomField($customdatatype->getKey(), $this->selected_customdatatype[$customdatatype->getKey()]);
				}
			}
			
			// FIXME: If we set the issue assignee during report issue, this needs to be set INSTEAD of this
			if ($this->selected_project->canAutoassign())
			{
				if (isset($fields_array['component']) && $this->selected_component instanceof TBGComponent && $this->selected_component->hasLeader())
				{
					$issue->setAssignee($this->selected_component->getLeader());
				}
				elseif (isset($fields_array['edition']) && $this->selected_edition instanceof TBGEdition && $this->selected_edition->hasLeader())
				{
					$issue->setAssignee($this->selected_edition->getLeader());
				}
				elseif ($this->selected_project->hasLeader())
				{
					$issue->setAssignee($this->selected_project->getLeader());
				}
			}
			
			$issue->save();

			if (isset($fields_array['edition']) && $this->selected_edition instanceof TBGEdition) $issue->addAffectedEdition($this->selected_edition);
			if (isset($fields_array['build']) && $this->selected_build instanceof TBGBuild) $issue->addAffectedBuild($this->selected_build);
			if (isset($fields_array['component']) && $this->selected_component instanceof TBGComponent) $issue->addAffectedComponent($this->selected_component);

			return $issue;
		}
		
		/**
		 * "Report issue" page
		 *  
		 * @param TBGRequest $request
		 */
		public function runReportIssue(TBGRequest $request)
		{
			$i18n = TBGContext::getI18n();
			$this->_setupReportIssueProperties();
			$errors = array();
			$permission_errors = array();
			$this->getResponse()->setPage('reportissue');
			$this->default_title = $i18n->__('Enter a short, but descriptive summary of the issue here');
			$this->default_estimated_time = $i18n->__('Enter an estimate here');
			$this->default_spent_time = $i18n->__('Enter time spent here');

			$this->_loadSelectedProjectAndIssueTypeFromRequestForReportIssueAction($request);
			
			$this->forward403unless(TBGContext::getUser()->canReportIssues(TBGContext::getCurrentProject()));
			
			if ($request->isMethod(TBGRequest::POST))
			{
				if ($this->_postIssueValidation($request, $errors, $permission_errors))
				{
					try
					{
						$issue = $this->_postIssue();
						if ($request->getParameter('return_format') == 'scrum')
						{
							return $this->renderJSON(array('failed' => false, 'story_id' => $issue->getID(), 'content' => $this->getComponentHTML('project/scrumcard', array('issue' => $issue))));
						}
						if ($issue->getProject()->getIssuetypeScheme()->isIssuetypeRedirectedAfterReporting($this->selected_issuetype))
						{
							$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())), 303);
						}
						else
						{
							$this->_clearReportIssueProperties();
							$this->issue = $issue;
						}
					}
					catch (Exception $e)
					{
						if ($request->getParameter('return_format') == 'scrum')
						{
							return $this->renderJSON(array('failed' => true, 'error' => $e->getMessage()));
						}
						$errors[] = $e->getMessage();
					}
				}
			}
			if ($request->getParameter('return_format') == 'scrum')
			{
				return $this->renderJSON(array('failed' => true, 'error' => __('You have to specify a title')));
			}
			$this->errors = $errors;
			$this->permission_errors = $permission_errors;
		}
		
		/**
		 * Retrieves the fields which are valid for that product and issue type combination
		 *  
		 * @param TBGRequest $request
		 */
		public function runReportIssueGetFields(TBGRequest $request)
		{
			if (!$this->selected_project instanceof TBGProject)
			{
				return $this->renderText('invalid project');
			}
			
			$fields_array = $this->selected_project->getReportableFieldsArray($request->getParameter('issuetype_id'));
			$available_fields = TBGDatatypeBase::getAvailableFields();
			$available_fields[] = 'pain_bug_type';
			$available_fields[] = 'pain_likelihood';
			$available_fields[] = 'pain_effect';
			return $this->renderJSON(array('available_fields' => $available_fields, 'fields' => $fields_array));
		}

		/**
		 * Retrieves the fields which are valid for that product and issue type combination
		 *  
		 * @param TBGRequest $request
		 */
		public function runToggleFavouriteIssue(TBGRequest $request)
		{
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->renderText('fail');
				}
			}
			else
			{
				return $this->renderText('no issue');
			}
			
			if (TBGContext::getUser()->isIssueStarred($issue_id))
			{
				$retval = !TBGContext::getUser()->removeStarredIssue($issue_id);
			}
			else
			{
				$retval = TBGContext::getUser()->addStarredIssue($issue_id);
			}
			return $this->renderText(json_encode(array('starred' => $retval)));
		}
		
		public function _setFieldFromRequest(TBGRequest $request)
		{
			
		}

		/**
		 * Sets an issue field to a specified value
		 * 
		 * @param TBGRequest $request
		 */
		public function runIssueSetField(TBGRequest $request)
		{
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					$this->getResponse()->setHttpStatus(400);
					return $this->renderText('fail');
				}
			}
			else
			{
				$this->getResponse()->setHttpStatus(400);
				return $this->renderText('no issue');
			}

			TBGContext::loadLibrary('common');
			
			if (!$issue instanceof TBGIssue) return false;
			
			switch ($request->getParameter('field'))
			{
				case 'description':
					if (!$issue->canEditDescription()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					
					$issue->setDescription($request->getRawParameter('value'));
					return $this->renderJSON(array('changed' => $issue->isDescriptionChanged(), 'field' => array('id' => (int) ($issue->getDescription() != ''), 'name' => tbg_parse_text($issue->getDescription(), false, null, array('issue' => $issue))), 'description' => tbg_parse_text($issue->getDescription(), false, null, array('issue' => $issue))));
					break;
				case 'reproduction_steps':
					if (!$issue->canEditReproductionSteps()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					
					$issue->setReproductionSteps($request->getRawParameter('value'));
					return $this->renderJSON(array('changed' => $issue->isReproductionStepsChanged(), 'field' => array('id' => (int) ($issue->getReproductionSteps() != ''), 'name' => tbg_parse_text($issue->getReproductionSteps(), false, null, array('issue' => $issue))), 'reproduction_steps' => tbg_parse_text($issue->getReproductionSteps(), false, null, array('issue' => $issue))));
					break;
				case 'title':
					if (!$issue->canEditTitle()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					
					if ($request->getParameter('value') == '')
					{
						return $this->renderJSON(array('changed' => false, 'failed' => true, 'error' => TBGContext::getI18n()->__('You have to provide a title')));
					}
					else
					{
						$issue->setTitle($request->getParameter('value'));
						return $this->renderJSON(array('changed' => $issue->isTitleChanged(), 'field' => array('id' => 1, 'name' => strip_tags($issue->getTitle())), 'title' => strip_tags($issue->getTitle())));
					}
					break;
				case 'percent':
					if (!$issue->canEditPercentage()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					
					$issue->setPercentCompleted($request->getParameter('percent'));
					return $this->renderJSON(array('changed' => $issue->isPercentCompletedChanged(), 'percent' => $issue->getPercentCompleted()));
					break;
				case 'estimated_time':
					if (!$issue->canEditEstimatedTime()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					
					if ($request->getParameter('estimated_time') != TBGContext::getI18n()->__('Enter your estimate here') && $request->getParameter('estimated_time'))
					{
						$issue->setEstimatedTime($request->getParameter('estimated_time'));
					}
					elseif ($request->hasParameter('value'))
					{
						$issue->setEstimatedTime($request->getParameter('value'));
					}
					else
					{
						$issue->setEstimatedMonths($request->getParameter('estimated_time_months'));
						$issue->setEstimatedWeeks($request->getParameter('estimated_time_weeks'));
						$issue->setEstimatedDays($request->getParameter('estimated_time_days'));
						$issue->setEstimatedHours($request->getParameter('estimated_time_hours'));
						$issue->setEstimatedPoints($request->getParameter('estimated_time_points'));
					}
					return $this->renderJSON(array('changed' => $issue->isEstimatedTimeChanged(), 'field' => (($issue->hasEstimatedTime()) ? array('id' => 1, 'name' => $issue->getFormattedTime($issue->getEstimatedTime())) : array('id' => 0)), 'values' => $issue->getEstimatedTime()));
					break;
				case 'owned_by':
				case 'posted_by':
				case 'assigned_to':
					if ($request->getParameter('field') == 'owned_by' && !$issue->canEditOwnedBy()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'posted_by' && !$issue->canEditPostedBy()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'assigned_to' && !$issue->canEditAssignedTo()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					
					if ($request->hasParameter('value'))
					{
						if ($request->hasParameter('identifiable_type'))
						{
							if (in_array($request->getParameter('identifiable_type'), array(TBGIdentifiableClass::TYPE_USER, TBGIdentifiableClass::TYPE_TEAM)))
							{
								switch ($request->getParameter('identifiable_type'))
								{
									case TBGIdentifiableClass::TYPE_USER:
										$identified = TBGContext::factory()->TBGUser($request->getParameter('value'));
										break;
									case TBGIdentifiableClass::TYPE_TEAM:
										$identified = TBGContext::factory()->TBGTeam($request->getParameter('value'));
										break;
								}
								if ($identified instanceof TBGIdentifiableClass)
								{
									if ((bool) $request->getParameter('teamup', false))
									{
										$team = new TBGTeam();
										$team->setName($identified->getBuddyname() . ' & ' . TBGContext::getUser()->getBuddyname());
										$team->setOndemand(true);
										$team->save();
										$team->addMember($identified);
										$team->addMember(TBGContext::getUser());
										$identified = $team;
									}
									if ($request->getParameter('field') == 'owned_by') $issue->setOwner($identified);
									elseif ($request->getParameter('field') == 'assigned_to') $issue->setAssignee($identified);
								}
							}
							else
							{
								if ($request->getParameter('field') == 'owned_by') $issue->unsetOwner();
								elseif ($request->getParameter('field') == 'assigned_to') $issue->unsetAssignee();
							}
						}
						elseif ($request->getParameter('field') == 'posted_by')
						{
							$identified = TBGContext::factory()->TBGUser($request->getParameter('value'));
							if ($identified instanceof TBGIdentifiableClass)
							{
								$issue->setPostedBy($identified);
							}
						}
						if ($request->getParameter('field') == 'owned_by')
							return $this->renderJSON(array('changed' => $issue->isOwnedByChanged(), 'field' => (($issue->isOwned()) ? array('id' => $issue->getOwnerID(), 'name' => (($issue->getOwnerType() == TBGIdentifiableClass::TYPE_USER) ? $this->getComponentHTML('main/userdropdown', array('user' => $issue->getOwner())) : $this->getComponentHTML('main/teamdropdown', array('team' => $issue->getOwner())))) : array('id' => 0))));
						if ($request->getParameter('field') == 'posted_by')
							return $this->renderJSON(array('changed' => $issue->isPostedByChanged(), 'field' => array('id' => $issue->getPostedByID(), 'name' => $this->getComponentHTML('main/userdropdown', array('user' => $issue->getPostedBy())))));
						if ($request->getParameter('field') == 'assigned_to')
							return $this->renderJSON(array('changed' => $issue->isAssignedToChanged(), 'field' => (($issue->isAssigned()) ? array('id' => $issue->getAssigneeID(), 'name' => (($issue->getAssigneeType() == TBGIdentifiableClass::TYPE_USER) ? $this->getComponentHTML('main/userdropdown', array('user' => $issue->getAssignee())) : $this->getComponentHTML('main/teamdropdown', array('team' => $issue->getAssignee())))) : array('id' => 0))));
					}
					break;
				case 'spent_time':
					if (!$issue->canEditSpentTime()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					
					if ($request->getParameter('spent_time') != TBGContext::getI18n()->__('Enter time spent here') && $request->getParameter('spent_time'))
					{
						$function = ($request->hasParameter('spent_time_added_text')) ? 'addSpentTime' : 'setSpentTime';
						$issue->$function($request->getParameter('spent_time'));
					}
					elseif ($request->hasParameter('value'))
					{
						$issue->setSpentTime($request->getParameter('value'));
					}
					else
					{
						if ($request->hasParameter('spent_time_added_input'))
						{
							$issue->addSpentMonths($request->getParameter('spent_time_months'));
							$issue->addSpentWeeks($request->getParameter('spent_time_weeks'));
							$issue->addSpentDays($request->getParameter('spent_time_days'));
							$issue->addSpentHours($request->getParameter('spent_time_hours'));
							$issue->addSpentPoints($request->getParameter('spent_time_points'));
						}
						else
						{
							$issue->setSpentMonths($request->getParameter('spent_time_months'));
							$issue->setSpentWeeks($request->getParameter('spent_time_weeks'));
							$issue->setSpentDays($request->getParameter('spent_time_days'));
							$issue->setSpentHours($request->getParameter('spent_time_hours'));
							$issue->setSpentPoints($request->getParameter('spent_time_points'));
						}
					}
					return $this->renderJSON(array('changed' => $issue->isSpentTimeChanged(), 'field' => (($issue->hasSpentTime()) ? array('id' => 1, 'name' => $issue->getFormattedTime($issue->getSpentTime())) : array('id' => 0)), 'values' => $issue->getSpentTime()));
					break;
				case 'category':
				case 'resolution':
				case 'severity':
				case 'reproducability':
				case 'priority':
				case 'milestone':
				case 'issuetype':
				case 'status':
				case 'pain_bug_type':
				case 'pain_likelihood':
				case 'pain_effect':
					if ($request->getParameter('field') == 'category' && !$issue->canEditCategory()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'resolution' && !$issue->canEditResolution()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'severity' && !$issue->canEditSeverity()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'reproducability' && !$issue->canEditReproducability()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'priority' && !$issue->canEditPriority()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'milestone' && !$issue->canEditMilestone()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'issuetype' && !$issue->canEditIssuetype()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif ($request->getParameter('field') == 'status' && !$issue->canEditStatus()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					elseif (in_array($request->getParameter('field'), array('pain_bug_type', 'pain_likelihood', 'pain_effect')) && !$issue->canEditUserPain()) return $this->renderJSON(array('changed' => false, 'error' => TBGContext::getI18n()->__('You do not have permission to perform this action')));
					
					try
					{
						$parameter_name = strtolower($request->getParameter('field'));
						$parameter_id_name = "{$parameter_name}_id";
						$is_pain = in_array($parameter_name, array('pain_bug_type', 'pain_likelihood', 'pain_effect'));
						if ($is_pain)
						{
							switch ($parameter_name)
							{
								case 'pain_bug_type':
									$set_function_name = 'setPainBugType';
									$is_changed_function_name = 'isPainBugTypeChanged';
									$get_pain_type_label_function = 'getPainBugTypeLabel';
									break;
								case 'pain_likelihood':
									$set_function_name = 'setPainLikelihood';
									$is_changed_function_name = 'isPainLikelihoodChanged';
									$get_pain_type_label_function = 'getPainLikelihoodLabel';
									break;
								case 'pain_effect':
									$set_function_name = 'setPainEffect';
									$is_changed_function_name = 'isPainEffectChanged';
									$get_pain_type_label_function = 'getPainEffectLabel';
									break;
							}
						}
						else
						{
							$classname = 'TBG'.ucfirst($parameter_name);
							$lab_function_name = $classname;
							$set_function_name = 'set'.ucfirst($parameter_name);
							$is_changed_function_name = 'is'.ucfirst($parameter_name).'Changed';
						}
						if ($request->hasParameter($parameter_id_name)) //$request->getParameter('field') == 'pain_bug_type')
						{
							$parameter_id = $request->getParameter($parameter_id_name);
							if ($parameter_id !== 0)
							{
								$is_valid = ($is_pain) ? in_array($parameter_id, array_keys(TBGIssue::getPainTypesOrLabel($parameter_name))) : ($parameter_id == 0 || (($parameter = TBGContext::factory()->$lab_function_name($parameter_id)) instanceof TBGIdentifiableClass));
							}
							if ($parameter_id == 0 || ($parameter_id !== 0 && $is_valid))
							{
								if ($classname == 'TBGIssuetype')
								{
									$visible_fields = ($issue->getIssuetype() instanceof TBGIssuetype) ? $issue->getProject()->getVisibleFieldsArray($issue->getIssuetype()->getID()) : array();
								}
								else
								{
									$visible_fields = null;
								}
								$issue->$set_function_name($parameter_id);
								if ($is_pain)
								{
									if (!$issue->$is_changed_function_name()) return $this->renderJSON(array('changed' => false, 'field' => array('id' => 0), 'user_pain' => $issue->getUserPain(), 'user_pain_diff_text' => $issue->getUserPainDiffText()));
									return ($parameter_id == 0) ? $this->renderJSON(array('changed' => true, 'field' => array('id' => 0), 'user_pain' => $issue->getUserPain(), 'user_pain_diff_text' => $issue->getUserPainDiffText())) : $this->renderJSON(array('changed' => true, 'field' => array('id' => $parameter_id, 'name' => $issue->$get_pain_type_label_function()), 'user_pain' => $issue->getUserPain(), 'user_pain_diff_text' => $issue->getUserPainDiffText()));
								}
								else
								{
									if (!$issue->$is_changed_function_name()) return $this->renderJSON(array('changed' => false));
									
									if (isset($parameter))
									{
										$name = $parameter->getName();
									}
									else
									{
										$name = null;
									}
									
									$field = array('id' => $parameter_id, 'name' => $name);
									if ($classname == 'TBGIssuetype')
									{
										TBGContext::loadLibrary('ui');
										$field['src'] = htmlspecialchars(TBGSettings::getURLsubdir() . 'themes/' . TBGSettings::getThemeName() . '/' . $issue->getIssuetype()->getIcon() . '_small.png');
									}
									return ($parameter_id == 0) ? $this->renderJSON(array('changed' => true, 'field' => array('id' => 0))) : $this->renderJSON(array('changed' => true, 'visible_fields' => $visible_fields, 'field' => $field));
								}
							}
						}
					}
					catch (Exception $e)
					{
						$this->getResponse()->setHttpStatus(400);
						return $this->renderJSON(array('error' => $e->getMessage()));
					}
					$this->getResponse()->setHttpStatus(400);
					return $this->renderJSON(array('error' => TBGContext::getI18n()->__('No valid field value specified')));
					break;
				default:
					if ($customdatatype = TBGCustomDatatype::getByKey($request->getParameter('field')))
					{
						$key = $customdatatype->getKey();
						
						$customdatatypeoption_value = $request->getParameter("{$key}_value");
						if (!$customdatatype->hasCustomOptions())
						{
							switch ($customdatatype->getType())
							{
								case TBGCustomDatatype::EDITIONS_CHOICE:
								case TBGCustomDatatype::COMPONENTS_CHOICE:
								case TBGCustomDatatype::RELEASES_CHOICE:
								case TBGCustomDatatype::STATUS_CHOICE:
									if ($customdatatypeoption_value == '')
									{
										$issue->setCustomField($key, "");
									}
									else
									{
										switch ($customdatatype->getType())
										{
											case TBGCustomDatatype::EDITIONS_CHOICE:
												$temp = new TBGEdition($request->getRawParameter("{$key}_value"));
												$finalvalue = $temp->getName();
												break;
											case TBGCustomDatatype::COMPONENTS_CHOICE:
												$temp = new TBGComponent($request->getRawParameter("{$key}_value"));
												$finalvalue = $temp->getName();
												break;
											case TBGCustomDatatype::RELEASES_CHOICE:
												$temp = new TBGBuild($request->getRawParameter("{$key}_value"));
												$finalvalue = $temp->getName();
												break;
											case TBGCustomDatatype::STATUS_CHOICE:
												$temp = new TBGStatus($request->getRawParameter("{$key}_value"));
												$finalvalue = $temp->getName();
												break;
										}
										$issue->setCustomField($key, $request->getRawParameter("{$key}_value"));
									}

									if (isset($temp) && $customdatatype->getType() == TBGCustomDatatype::STATUS_CHOICE && is_object($temp))
									{
										$finalvalue = '<div style="border: 1px solid #AAA; background-color: '.$temp->getColor().'; font-size: 1px; width: 20px; height: 15px; margin-right: 5px; float: left;" id="status_color">&nbsp;</div>'.$finalvalue;
									}

									$changed_methodname = "isCustomfield{$key}Changed";
									if (!$issue->$changed_methodname()) return $this->renderJSON(array('changed' => false));
									return ($customdatatypeoption_value == '') ? $this->renderJSON(array('changed' => true, 'field' => array('id' => 0))) : $this->renderJSON(array('changed' => true, 'field' => array('value' => $key, 'name' => $finalvalue)));
									break;
								case TBGCustomDatatype::INPUT_TEXTAREA_MAIN:
								case TBGCustomDatatype::INPUT_TEXTAREA_SMALL:
									if ($customdatatypeoption_value == '')
									{
										$issue->setCustomField($key, "");
									}
									else
									{
										$issue->setCustomField($key, $request->getRawParameter("{$key}_value"));
									}
									$changed_methodname = "isCustomfield{$key}Changed";
									if (!$issue->$changed_methodname()) return $this->renderJSON(array('changed' => false));
									return ($customdatatypeoption_value == '') ? $this->renderJSON(array('changed' => true, 'field' => array('id' => 0))) : $this->renderJSON(array('changed' => true, 'field' => array('value' => $key, 'name' => tbg_parse_text($request->getRawParameter("{$key}_value"), false, null, array('issue' => $issue)))));
									break;
								default:
									if ($customdatatypeoption_value == '')
									{
										$issue->setCustomField($key, "");
									}
									else
									{
										$issue->setCustomField($key, $request->getParameter("{$key}_value"));
									}
									$changed_methodname = "isCustomfield{$key}Changed";
									if (!$issue->$changed_methodname()) return $this->renderJSON(array('changed' => false));
									return ($customdatatypeoption_value == '') ? $this->renderJSON(array('changed' => true, 'field' => array('id' => 0))) : $this->renderJSON(array('changed' => true, 'field' => array('value' => $key, 'name' => $customdatatypeoption_value)));
									break;
							}
						}
						if ($customdatatypeoption_value == '' || ($customdatatypeoption_value && ($customdatatypeoption = TBGCustomDatatypeOption::getByValueAndKey($customdatatypeoption_value, $key)) instanceof TBGCustomDatatypeOption))
						{
							if ($customdatatypeoption_value == '')
							{
								$issue->setCustomField($key, "");
							}
							else
							{
								$issue->setCustomField($key, $customdatatypeoption->getValue());
							}
							
							$changed_methodname = "isCustomfield{$key}Changed";
							if (!$issue->$changed_methodname()) return $this->renderJSON(array('changed' => false));
							return ($customdatatypeoption_value == '') ? $this->renderJSON(array('changed' => true, 'field' => array('id' => 0))) : $this->renderJSON(array('changed' => true, 'field' => array('value' => $customdatatypeoption_value, 'name' => $customdatatypeoption->getName())));
						}
					}
					break;
			}
			
			$this->getResponse()->setHttpStatus(400);
			return $this->renderJSON(array('error' => TBGContext::getI18n()->__('No valid field specified (%field%)', array('%field%' => $request->getParameter('field')))));
		}

		/**
		 * Reverts an issue field back to the original value
		 * 
		 * @param TBGRequest $request
		 */
		public function runIssueRevertField(TBGRequest $request)
		{
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					$this->getResponse()->setHttpStatus(400);
					return $this->renderText('fail');
				}
			}
			else
			{
				$this->getResponse()->setHttpStatus(400);
				return $this->renderText('no issue');
			}
			
			$field = null;
			TBGContext::loadLibrary('common');
			switch ($request->getParameter('field'))
			{
				case 'description':
					$issue->revertDescription();
					$field = array('id' => (int) ($issue->getDescription() != ''), 'name' => tbg_parse_text($issue->getDescription()), 'form_value' => $issue->getDescription());
					break;
				case 'reproduction_steps':
					$issue->revertReproduction_Steps();
					$field = array('id' => (int) ($issue->getReproductionSteps() != ''), 'name' => tbg_parse_text($issue->getReproductionSteps()), 'form_value' => $issue->getReproductionSteps());
					break;
				case 'title':
					$issue->revertTitle();
					$field = array('id' => 1, 'name' => strip_tags($issue->getTitle()));
					break;
				case 'category':
					$issue->revertCategory();
					$field = ($issue->getCategory() instanceof TBGCategory) ? array('id' => $issue->getCategory()->getID(), 'name' => $issue->getCategory()->getName()) : array('id' => 0);
					break;
				case 'resolution':
					$issue->revertResolution();
					$field = ($issue->getResolution() instanceof TBGResolution) ? array('id' => $issue->getResolution()->getID(), 'name' => $issue->getResolution()->getName()) : array('id' => 0);
					break;
				case 'severity':
					$issue->revertSeverity();
					$field = ($issue->getSeverity() instanceof TBGSeverity) ? array('id' => $issue->getSeverity()->getID(), 'name' => $issue->getSeverity()->getName()) : array('id' => 0);
					break;
				case 'reproducability':
					$issue->revertReproducability();
					$field = ($issue->getReproducability() instanceof TBGReproducability) ? array('id' => $issue->getReproducability()->getID(), 'name' => $issue->getReproducability()->getName()) : array('id' => 0);
					break;
				case 'priority':
					$issue->revertPriority();
					$field = ($issue->getPriority() instanceof TBGPriority) ? array('id' => $issue->getPriority()->getID(), 'name' => $issue->getPriority()->getName()) : array('id' => 0);
					break;
				case 'percent':
					$issue->revertPercentCompleted();
					return $this->renderJSON(array('ok' => true, 'percent' => $issue->getPercentCompleted()));
					break;
				case 'status':
					$issue->revertStatus();
					$field = ($issue->getStatus() instanceof TBGStatus) ? array('id' => $issue->getStatus()->getID(), 'name' => $issue->getStatus()->getName(), 'color' => $issue->getStatus()->getColor()) : array('id' => 0);
					break;
				case 'pain_bug_type':
					$issue->revertPainBugType();
					$field = ($issue->hasPainBugType()) ? array('id' => $issue->getPainBugType(), 'name' => $issue->getPainBugTypeLabel(), 'user_pain' => $issue->getUserPain()) : array('id' => 0, 'user_pain' => $issue->getUserPain());
					break;
				case 'pain_likelihood':
					$issue->revertPainLikelihood();
					$field = ($issue->hasPainLikelihood()) ? array('id' => $issue->getPainLikelihood(), 'name' => $issue->getPainLikelihoodLabel(), 'user_pain' => $issue->getUserPain()) : array('id' => 0, 'user_pain' => $issue->getUserPain());
					break;
				case 'pain_effect':
					$issue->revertPainEffect();
					$field = ($issue->hasPainEffect()) ? array('id' => $issue->getPainEffect(), 'name' => $issue->getPainEffectLabel(), 'user_pain' => $issue->getUserPain()) : array('id' => 0, 'user_pain' => $issue->getUserPain());
					break;
				case 'issuetype':
					$issue->revertIssuetype();
					$field = ($issue->getIssuetype() instanceof TBGIssuetype) ? array('id' => $issue->getIssuetype()->getID(), 'name' => $issue->getIssuetype()->getName(), 'src' => htmlspecialchars(TBGSettings::getURLsubdir() . 'themes/' . TBGSettings::getThemeName() . '/' . $issue->getIssuetype()->getIcon() . '_small.png')) : array('id' => 0);
					$visible_fields = ($issue->getIssuetype() instanceof TBGIssuetype) ? $issue->getProject()->getVisibleFieldsArray($issue->getIssuetype()->getID()) : array();
					return $this->renderJSON(array('ok' => true, 'field' => $field, 'visible_fields' => $visible_fields));
					break;
				case 'milestone':
					$issue->revertMilestone();
					$field = ($issue->getMilestone() instanceof TBGMilestone) ? array('id' => $issue->getMilestone()->getID(), 'name' => $issue->getMilestone()->getName()) : array('id' => 0);
					break;
				case 'estimated_time':
					$issue->revertEstimatedTime();
					return $this->renderJSON(array('ok' => true, 'field' => (($issue->hasEstimatedTime()) ? array('id' => 1, 'name' => $issue->getFormattedTime($issue->getEstimatedTime())) : array('id' => 0)), 'values' => $issue->getEstimatedTime()));
					break;
				case 'spent_time':
					$issue->revertSpentTime();
					return $this->renderJSON(array('ok' => true, 'field' => (($issue->hasSpentTime()) ? array('id' => 1, 'name' => $issue->getFormattedTime($issue->getSpentTime())) : array('id' => 0)), 'values' => $issue->getSpentTime()));
					break;
				case 'owned_by':
					$issue->revertOwnedBy();
					return $this->renderJSON(array('changed' => $issue->isOwnedByChanged(), 'field' => (($issue->isOwned()) ? array('id' => $issue->getOwnerID(), 'name' => (($issue->getOwnerType() == TBGIdentifiableClass::TYPE_USER) ? $this->getComponentHTML('main/userdropdown', array('user' => $issue->getOwner())) : $this->getComponentHTML('main/teamdropdown', array('team' => $issue->getOwner())))) : array('id' => 0))));
					break;
				case 'assigned_to':
					$issue->revertAssignedTo();
					return $this->renderJSON(array('changed' => $issue->isAssignedToChanged(), 'field' => (($issue->isAssigned()) ? array('id' => $issue->getAssigneeID(), 'name' => (($issue->getAssigneeType() == TBGIdentifiableClass::TYPE_USER) ? $this->getComponentHTML('main/userdropdown', array('user' => $issue->getAssignee())) : $this->getComponentHTML('main/teamdropdown', array('team' => $issue->getAssignee())))) : array('id' => 0))));
					break;
				case 'posted_by':
					$issue->revertPostedBy();
					return $this->renderJSON(array('changed' => $issue->isPostedByChanged(), 'field' => array('id' => $issue->getPostedByID(), 'name' => $this->getComponentHTML('main/userdropdown', array('user' => $issue->getPostedBy())))));
					break;
				default:
					if ($customdatatype = TBGCustomDatatype::getByKey($request->getParameter('field')))
					{
						$key = $customdatatype->getKey();
						$revert_methodname = "revertCustomfield{$key}";
						$issue->$revert_methodname();
						
						if ($customdatatype->hasCustomOptions())
						{
							$field = ($issue->getCustomField($key) instanceof TBGCustomDatatypeOption) ? array('value' => $issue->getCustomField($key)->getValue(), 'name' => $issue->getCustomField($key)->getName()) : array('id' => 0);
						}
						else
						{
							switch ($customdatatype->getType())
							{
								case TBGCustomDatatype::INPUT_TEXTAREA_MAIN:
								case TBGCustomDatatype::INPUT_TEXTAREA_SMALL:
									$field = ($issue->getCustomField($key) != '') ? array('value' => $key, 'name' => tbg_parse_text($issue->getCustomField($key))) : array('id' => 0);
									break;
								default:
									$field = ($issue->getCustomField($key) != '') ? array('value' => $key, 'name' => $issue->getCustomField($key)) : array('id' => 0);
									break;
							}							
						}
					}
					break;
			}
			
			if ($field !== null)
			{
				return $this->renderJSON(array('ok' => true, 'field' => $field));
			}
			else
			{
				$this->getResponse()->setHttpStatus(400);
				return $this->renderJSON(array('error' => TBGContext::getI18n()->__('No valid field specified (%field%)', array('%field%' => $request->getParameter('field')))));
			}
		}
		
		/**
		 * Marks this issue as being worked on by the current user
		 * 
		 * @param TBGRequest $request
		 */
		public function runIssueStartWorking(TBGRequest $request)
		{
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}
			$issue->startWorkingOnIssue(TBGContext::getUser());
			$issue->save();
			$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
		}
		
		/**
		 * Marks this issue as being completed work on by the current user
		 * 
		 * @param TBGRequest $request
		 */
		public function runIssueStopWorking(TBGRequest $request)
		{
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}
			
			if ($request->hasParameter('did') && $request->getParameter('did') == 'nothing')
			{
				$issue->clearUserWorkingOnIssue();
				$issue->save();
				$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
			}
			elseif ($request->hasParameter('perform_action') && $request->getParameter('perform_action') == 'grab')
			{
				$issue->clearUserWorkingOnIssue();
				$issue->startWorkingOnIssue(TBGContext::getUser());
				$issue->save();
				$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
			}
			else
			{
				$issue->stopWorkingOnIssue();
				$issue->save();
				$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
			}
		}

		/**
		 * Reopen the issue
		 * 
		 * @param TBGRequest $request
		 */
		public function runReopenIssue(TBGRequest $request)
		{
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}
			$issue->open();
			$issue->save();
			$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
		}
		
		/**
		 * Close the issue
		 * 
		 * @param TBGRequest $request
		 */
		public function runCloseIssue(TBGRequest $request)
		{
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}
			if ($request->hasParameter('set_status'))
			{
				$issue->setStatus($request->getParameter('status_id'));
			}
			if ($request->hasParameter('set_resolution'))
			{
				$issue->setResolution($request->getParameter('resolution_id'));
			}
			if (trim($request->getParameter('close_comment')) != '')
			{
				$issue->addSystemComment(TBGContext::getI18n()->__('Issue closed'), $request->getParameter('close_comment'), TBGContext::getUser()->getID());
			}
			$issue->close();
			$issue->save();
			$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
		}
		
		/**
		 * Mark the issue as a duplicate of another
		 * 
		 * @param TBGRequest $request
		 */
		public function runMarkAsDuplicate(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPermission('caneditissue') || TBGContext::getUser()->hasPermission('caneditissuebasic'));
			
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}
			try
			{
				$issue2 = TBGContext::factory()->TBGIssue($request->getParameter('duplicate_issue'));
			}
			catch (Exception $e)
			{
				return $this->return404(TBGContext::getI18n()->__('The issue to be set as the duplicate does not exist'));
			}
			if ($request->hasParameter('set_status'))
			{
				$issue->setStatus($request->getParameter('status_id'));
			}
			if ($request->hasParameter('set_resolution'))
			{
				$issue->setResolution($request->getParameter('resolution_id'));
			}
			if (trim($request->getParameter('markasduplicate_comment')) != '')
			{
				$issue->addSystemComment(TBGContext::getI18n()->__('Issue marked as a duplicate'), $request->getParameter('markasduplicate_comment'), TBGContext::getUser()->getID());
			}
			else
			{
				$issue->addSystemComment(TBGContext::getI18n()->__('Issue marked as a duplicate'), TBGContext::getI18n()->__('This issue is now a duplicate of %issue%', array('%issue%' => $issue2->getFormattedIssueNo(true, true))), TBGContext::getUser()->getID());
			}
			if ($request->hasParameter('set_close'))
			{
				$issue->close();
			}
			$issue->setDuplicateOf($request->getParameter('duplicate_issue'));
			$issue->save();
			$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
		}
		
		/**
		 * Mark the issue as not a duplicate of another
		 * 
		 * @param TBGRequest $request
		 */
		public function runMarkAsNotDuplicate(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPermission('caneditissue') || TBGContext::getUser()->hasPermission('caneditissuebasic'));
			
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}

			$issue->setDuplicateOf(0);
			$issue->save();
			
			$issue->addSystemComment(TBGContext::getI18n()->__('Issue is no longer a duplicate'), TBGContext::getI18n()->__('This issue is no longer a duplicate of any other issues'), TBGContext::getUser()->getID());
			$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
		}
		
		/**
		 * Mark the issue as not blocking the next release
		 * 
		 * @param TBGRequest $request
		 */
		public function runMarkAsNotBlocker(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPermission('caneditissue') || TBGContext::getUser()->hasPermission('caneditissuebasic'));

			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}

			$issue->setBlocking(false);
			$issue->save();
			
			$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
		}
		
		/**
		 * Mark the issue as blocking the next release
		 * 
		 * @param TBGRequest $request
		 */
		public function runMarkAsBlocker(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPermission('caneditissue') || TBGContext::getUser()->hasPermission('caneditissuebasic'));
						
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}

			$issue->setBlocking();
			$issue->save();
			
			$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
		}
		
		/**
		 * Delete an issue
		 * 
		 * @param TBGRequest $request
		 */
		public function runDeleteIssue(TBGRequest $request)
		{
			$this->forward403unless(TBGContext::getUser()->hasPermission('candeleteissues'));

			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
				}
			}
			else
			{
				return $this->return404(TBGContext::getI18n()->__('This issue does not exist'));
			}

			$issue->deleteIssue();
			$issue->save();
			
			$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
		}
		
		/**
		 * Find users and show selection links
		 * 
		 * @param TBGRequest $request The request object
		 */		
		public function runFindIdentifiable(TBGRequest $request)
		{
			$this->forward403unless($request->isMethod(TBGRequest::POST));
			$this->users = array();
			
			if ($find_identifiable_by = $request->getParameter('find_identifiable_by'))
			{
				$this->users = TBGUser::findUsers($find_identifiable_by, 10);
				if ($request->getParameter('include_teams'))
				{
					$this->teams = TBGTeam::findTeams($find_identifiable_by);
				}
				else
				{
					$this->teams = array();
				}
			}
			$teamup_callback = $request->getParameter('teamup_callback');
			return $this->renderComponent('identifiableselectorresults', array('users' => $this->users, 'teams' => $this->teams, 'callback' => $request->getParameter('callback'), 'teamup_callback' => $teamup_callback));
		}
		
		/**
		 * Hides an infobox with a specific key
		 * 
		 * @param TBGRequest $request The request object
		 */		
		public function runHideInfobox(TBGRequest $request)
		{
			TBGSettings::hideInfoBox($request->getParameter('key'));
			return $this->renderJSON(array('hidden' => true));
		}

		public function runGetUploadStatus(TBGRequest $request)
		{
			$id = $request->getParameter('upload_id', 0);

			TBGLogging::log('requesting status for upload with id ' . $id);
			$status = TBGContext::getRequest()->getUploadStatus($id);
			TBGLogging::log('status was: ' . (int) $status['finished']. ', pct: '. (int) $status['percent']);
			if (array_key_exists('file_id', $status) && $request->getParameter('mode') == 'issue')
			{
				$file = TBGContext::factory()->TBGFile($status['file_id']);
				$status['content_uploader'] = $this->getComponentHTML('main/attachedfile', array('base_id' => 'uploaded_files', 'mode' => 'issue', 'issue_id' => $request->getParameter('issue_id'), 'file' => $file));
				$status['content_inline'] = $this->getComponentHTML('main/attachedfile', array('base_id' => 'viewissue_files', 'mode' => 'issue', 'issue_id' => $request->getParameter('issue_id'), 'file' => $file));
				$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
				$status['attachmentcount'] = count($issue->getFiles()) + count($issue->getLinks());
			}
			elseif (array_key_exists('file_id', $status) && $request->getParameter('mode') == 'article')
			{
				$file = TBGContext::factory()->TBGFile($status['file_id']);
				$status['content_uploader'] = $this->getComponentHTML('main/attachedfile', array('base_id' => 'article_'.strtolower(urldecode($request->getParameter('article_name'))).'_files', 'mode' => 'article', 'article_name' => $request->getParameter('article_name'), 'file' => $file));
				$status['content_inline'] = $this->getComponentHTML('main/attachedfile', array('base_id' => 'article_'.strtolower(urldecode($request->getParameter('article_name'))).'_files', 'mode' => 'article', 'article_name' => $request->getParameter('article_name'), 'file' => $file));
				$article = TBGWikiArticle::getByName($request->getParameter('article_name'));
				$status['attachmentcount'] = count($article->getFiles());
			}
			
			return $this->renderJSON($status);
		}

		public function runUpload(TBGRequest $request)
		{
			$apc_exists = TBGRequest::CanGetUploadStatus();
			if ($apc_exists && !$request->getParameter('APC_UPLOAD_PROGRESS'))
			{
				$request->setParameter('APC_UPLOAD_PROGRESS', $request->getParameter('upload_id'));
			}
			$this->getResponse()->setDecoration(TBGResponse::DECORATE_NONE);

			$canupload = false;

			if ($request->getParameter('mode') == 'issue')
			{
				$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
				$canupload = (bool) ($issue instanceof TBGIssue && $issue->hasAccess() && $issue->canAttachFiles());
			}
			elseif ($request->getParameter('mode') == 'article')
			{
				$article = TBGWikiArticle::getByName($request->getParameter('article_name'));
				$canupload = (bool) ($article instanceof TBGWikiArticle && $article->canEdit());
			}
			else
			{
				$event = TBGEvent::createNew('core', 'upload', $request->getParameter('mode'));
				$event->triggerUntilProcessed();

				$canupload = ($event->isProcessed()) ? (bool) $event->getReturnValue() : true;
			}
			
			if ($canupload)
			{
				try
				{
					$file = TBGContext::getRequest()->handleUpload('uploader_file');
					if ($file instanceof TBGFile)
					{
						switch ($request->getParameter('mode'))
						{
							case 'issue':
								if (!$issue instanceof TBGIssue) break;
								$issue->attachFile($file);
								$comment = new TBGComment();
								$comment->setPostedBy(TBGContext::getUser()->getID());
								$comment->setTargetID($issue->getID());
								$comment->setTargetType(TBGComment::TYPE_ISSUE);
								if ($request->getParameter('comment') != '')
								{
									$comment->setContent(TBGContext::getI18n()->__('A file was uploaded. %link_to_file% This comment was attached: %comment%', array('%comment%' => "\n\n".$request->getRawParameter('comment'), '%link_to_file%' => "[[File:{$file->getOriginalFilename()}|thumb|{$request->getParameter('uploader_file_description')}]]")));
								}
								else
								{
									$comment->setContent(TBGContext::getI18n()->__('A file was uploaded. %link_to_file%', array('%link_to_file%' => "[[File:{$file->getOriginalFilename()}|thumb|{$request->getParameter('uploader_file_description')}]]")));
								}
								$comment->save();
								break;
							case 'article':
								if (!$article instanceof TBGWikiArticle) break;
								$article->attachFile($file);
								break;
						}
						if ($apc_exists)
							return $this->renderText('ok');
					}
					$this->error = TBGContext::getI18n()->__('An unhandled error occured with the upload');
				}
				catch (Exception $e)
				{
					$this->getResponse()->setHttpStatus(400);
					$this->error = $e->getMessage();
				}
			}
			else
			{
				$this->getResponse()->setHttpStatus(401);
				$this->error = TBGContext::getI18n()->__('You are not allowed to attach files here');
			}
			if (!$apc_exists)
			{
				switch ($request->getParameter('mode'))
				{
					case 'issue':
						if (!$issue instanceof TBGIssue) break;
						$this->forward(TBGContext::getRouting()->generate('viewissue', array('project_key' => $issue->getProject()->getKey(), 'issue_no' => $issue->getFormattedIssueNo())));
						break;
					case 'article':
						if (!$article instanceof TBGWikiArticle) break;
						$this->forward(TBGContext::getRouting()->generate('publish_article_attachments', array('article_name' => $article->getName())));
						break;
				}
			}
			TBGLogging::log('marking upload ' . $request->getParameter('APC_UPLOAD_PROGRESS') . ' as completed with error ' . $this->error);
			$request->markUploadAsFinishedWithError($request->getParameter('APC_UPLOAD_PROGRESS'), $this->error);
			return $this->renderText($request->getParameter('APC_UPLOAD_PROGRESS').': '.$this->error);
		}

		public function runDetachFile(TBGrequest $request)
		{
			try
			{
				switch ($request->getParameter('mode'))
				{
					case 'issue':
						$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
						if ($issue->canRemoveAttachments() && (int) $request->getParameter('file_id', 0))
						{
							B2DB::getTable('TBGIssueFilesTable')->removeByIssueIDAndFileID($issue->getID(), (int) $request->getParameter('file_id'));
							return $this->renderJSON(array('failed' => false, 'file_id' => $request->getParameter('file_id'), 'attachmentcount' => (count($issue->getFiles()) + count($issue->getLinks())), 'message' => TBGContext::getI18n()->__('The attachment has been removed')));
						}
						return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You can not remove items from this issue')));
						break;
					case 'article':
						$article = TBGWikiArticle::getByName($request->getParameter('article_name'));
						if ($article instanceof TBGWikiArticle && $article->canEdit() && (int) $request->getParameter('file_id', 0))
						{
							$article->removeFile(TBGContext::factory()->TBGFile((int) $request->getParameter('file_id')));
							return $this->renderJSON(array('failed' => false, 'file_id' => $request->getParameter('file_id'), 'attachmentcount' => count($article->getFiles()), 'message' => TBGContext::getI18n()->__('The attachment has been removed')));
						}
						return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You can not remove items from this issue')));
						break;
				}
			}
			catch (Exception $e)
			{
				throw $e;
			}
			return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Invalid mode')));
		}

		public function runGetFile(TBGRequest $request)
		{
			$file = TBGFilesTable::getTable()->doSelectById((int) $request->getParameter('id'));
			if ($file instanceof B2DBRow)
			{
				$this->getResponse()->cleanBuffer();
				$this->getResponse()->clearHeaders();
				$this->getResponse()->setDecoration(TBGResponse::DECORATE_NONE);
				$this->getResponse()->addHeader('Content-disposition: '.(($request->getParameter('mode') == 'download') ? 'attachment' : 'inline').'; filename="'.$file->get(TBGFilesTable::ORIGINAL_FILENAME).'"');
				$this->getResponse()->addHeader('Content-type: '.$file->get(TBGFilesTable::CONTENT_TYPE));
				$this->getResponse()->renderHeaders();
				if (TBGSettings::getUploadStorage() == 'files')
				{
					echo fpassthru(fopen(TBGSettings::getUploadsLocalpath().$file->get(TBGFilesTable::REAL_FILENAME), 'r'));
					exit();
				}
				else
				{
					echo $file->get(TBGFilesTable::CONTENT);
					exit();
				}
				return true;
			}
			$this->return404(TBGContext::getI18n()->__('This file does not exist'));
		}

		public function runAttachLinkToIssue(TBGRequest $request)
		{
			$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
			if ($issue instanceof TBGIssue && $issue->canAttachLinks())
			{
				if ($request->getParameter('link_url') != '')
				{
					$link_id = $issue->attachLink($request->getParameter('link_url'), $request->getParameter('description'));
					return $this->renderJSON(array('failed' => false, 'message' => TBGContext::getI18n()->__('Link attached!'), 'attachmentcount' => (count($issue->getFiles()) + count($issue->getLinks())), 'content' => $this->getTemplateHTML('main/attachedlink', array('issue' => $issue, 'link_id' => $link_id, 'link' => array('description' => $request->getParameter('description'), 'url' => $request->getParameter('link_url'))))));
				}
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You have to provide a link URL, otherwise we have nowhere to link to!')));
			}
			return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You can not attach links to this issue')));
		}

		public function runRemoveLinkFromIssue(TBGRequest $request)
		{
			$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
			if ($issue instanceof TBGIssue && $issue->canRemoveAttachments())
			{
				if ($request->getParameter('link_id') != 0)
				{
					$issue->removeLink($request->getParameter('link_id'));
					return $this->renderJSON(array('failed' => false, 'attachmentcount' => (count($issue->getFiles()) + count($issue->getLinks())), 'message' => TBGContext::getI18n()->__('Link removed!')));
				}
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You have to provide a valid link id')));
			}
			return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You can not remove items from this issue')));
		}

		public function runAttachLink(TBGRequest $request)
		{
			$link_id = TBGLinksTable::getTable()->addLink($request->getParameter('target_type'), $request->getParameter('target_id'), $request->getParameter('link_url'), $request->getRawParameter('description'));
			return $this->renderJSON(array('failed' => false, 'message' => TBGContext::getI18n()->__('Link added!'), 'content' => $this->getTemplateHTML('main/menulink', array('link_id' => $link_id, 'link' => array('target_type' => $request->getParameter('target_type'), 'target_id' => $request->getParameter('target_id'), 'description' => $request->getRawParameter('description'), 'url' => $request->getParameter('link_url'))))));
		}

		public function runRemoveLink(TBGRequest $request)
		{
			if ($request->getParameter('link_id') != 0)
			{
				TBGLinksTable::getTable()->removeByTargetTypeTargetIDandLinkID($request->getParameter('target_type'), $request->getParameter('target_id'), $request->getParameter('link_id'));
				return $this->renderJSON(array('failed' => false, 'message' => TBGContext::getI18n()->__('Link removed!')));
			}
			return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You have to provide a valid link id')));
		}
		
		public function runDeleteComment(TBGRequest $request)
		{
			$comment = TBGContext::factory()->TBGComment($request->getParameter('comment_id'));
			if ($comment instanceof TBGcomment)
			{							
				if (!$comment->canUserDeleteComment())
				{
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You are not allowed to do this')));
				}
				else
				{
					unset($comment);
					$comment = TBGContext::factory()->TBGComment((int) $request->getParameter('comment_id'));
					$comment->delete();
					return $this->renderJSON(array('title' => TBGContext::getI18n()->__('Comment deleted!')));
				}
			}
			else
			{
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Comment ID is invalid')));
			}
		}
		
		public function runUpdateComment(TBGRequest $request)
		{
			TBGContext::loadLibrary('ui');
			$comment = TBGContext::factory()->TBGComment($request->getParameter('comment_id'));
			if ($comment instanceof TBGcomment)
			{							
				if (!$comment->canUserEditComment())
				{
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You are not allowed to do this')));
				}
				else
				{
					if ($request->getParameter('comment_body') == '')
					{
						return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('The comment must have some content')));
					}
					
					$comment->setContent($request->getParameter('comment_body'));
					
					if ($request->getParameter('comment_title') == '')
					{
						$comment->setTitle(TBGContext::getI18n()->__('Untitled comment'));
					}
					else
					{
						$comment->setTitle($request->getParameter('comment_title'));
					}
					
					$comment->setIsPublic($request->getParameter('comment_visibility'));
					$comment->setUpdatedBy(TBGContext::getUser()->getID());
					$body = tbg_parse_text($comment->getContent());
					
					return $this->renderJSON(array('title' => TBGContext::getI18n()->__('Comment edited!'), 'comment_title' => $comment->getTitle(), 'comment_body' => $body));
				}
			}
			else
			{
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Comment ID is invalid')));
			}
		}

		public function listenIssueSaveAddComment(TBGEvent $event)
		{
			$this->comment_lines = $event->getParameter('comment_lines');
			$this->comment = $event->getParameter('comment');
		}

		public function listenViewIssuePostError(TBGEvent $event)
		{
			if (TBGContext::hasMessage('comment_error'))
			{
				$this->comment_error = true;
				$this->error = TBGContext::getMessageAndClear('comment_error');
				$this->comment_error_title = TBGContext::getMessageAndClear('comment_error_title');
				$this->comment_error_body = TBGContext::getMessageAndClear('comment_error_body');
			}
		}
		
		public function runAddComment(TBGRequest $request)
		{
			$i18n = TBGContext::getI18n();
			$comment = null;
			$project = TBGContext::factory()->TBGProject($request->getParameter('project_id'));
			$project_key = ($project instanceof TBGProject) ? $project->getKey() : false;
			try
			{
				if ($project instanceof TBGProject)
				{
					if (!TBGContext::getUser()->canPostComments())
					{
						throw new Exception($i18n->__('You are not allowed to do this'));
					}
					else
					{
						if ($request->getParameter('comment_title') == '')
						{
							$title = $i18n->__('Untitled comment');
						}
						else
						{
							$title = $request->getParameter('comment_title');
						}

						if ($request->getParameter('comment_body') == '')
						{
							throw new Exception($i18n->__('The comment must have some content'));
						}

						if (!$request->isAjaxCall())
						{
							$this->comment_lines = array();
							$this->comment = '';
							TBGEvent::listen('core', 'TBGIssue::save', array($this, 'listenIssueSaveAddComment'));
							$issue = TBGContext::factory()->TBGIssue($request->getParameter('comment_applies_id'));
							$issue->save(false);
						}

						if (empty($this->comment) == false) // prevent empty lines when only user comment
						{
							$comment_body = $this->comment . "\n\n" . $request->getParameter('comment_body', null, false);
						}
						else
						{
							$comment_body = $request->getParameter('comment_body', null, false);
						}
						$comment = new TBGComment();
						$comment->setTitle($title);
						$comment->setContent($comment_body);
						$comment->setPostedBy(TBGContext::getUser()->getID());
						$comment->setTargetID($request->getParameter('comment_applies_id'));
						$comment->setTargetType($request->getParameter('comment_applies_type'));
						$comment->setModuleName($request->getParameter('comment_module'));
						$comment->setIsPublic((bool) $request->getParameter('comment_visibility'));
						$comment->save();

						if ($request->getParameter('comment_applies_type') == 1 && $request->getParameter('comment_module') == 'core')
						{
							$comment_html = $this->getTemplateHTML('main/comment', array('comment' => $comment, 'issue' => TBGContext::factory()->TBGIssue($request->getParameter('comment_applies_id'))));
						}
						else
						{
							$comment_html = 'OH NO!';
						}
					}
				}
				else
				{
					throw new Exception($i18n->__('Comment ID is invalid'));
				}
			}
			catch (Exception $e)
			{
				if ($request->isAjaxCall())
				{
					return $this->renderJSON(array('failed' => true, 'error' => $e->getMessage()));
				}
				else
				{
					TBGContext::setMessage('comment_error', $e->getMessage());
					TBGContext::setMessage('comment_error_body', $request->getParameter('comment_body'));
					TBGContext::setMessage('comment_error_title', $request->getParameter('comment_title'));
					TBGContext::setMessage('comment_error_visibility', $request->getParameter('comment_visibility'));
				}
			}
			if ($request->isAjaxCall())
			{
				return $this->renderJSON(array('title' => $i18n->__('Comment added!'), 'comment_data' => $comment_html, 'commentcount' => TBGComment::countComments($request->getParameter('comment_applies_id'), $request->getParameter('comment_applies_type')/*, $request->getParameter('comment_module')*/)));
			}
			if ($comment instanceof TBGComment)
			{
				$this->forward($request->getParameter('forward_url') . "#comment_{$request->getParameter('comment_applies_type')}_{$request->getParameter('comment_applies_id')}_{$comment->getID()}");
			}
			else
			{
				$this->forward($request->getParameter('forward_url'));
			}
		}

		public function runListProjects(TBGRequest $request)
		{
			$projects = TBGProject::getAll();

			$return_array = array();
			foreach ($projects as $project)
			{
				$return_array[$project->getKey()] = $project->getName();
			}

			$this->projects = $return_array;
		}

		public function runListIssuetypes(TBGRequest $request)
		{
			$issuetypes = TBGIssuetype::getAll();

			$return_array = array();
			foreach ($issuetypes as $issuetype)
			{
				$return_array[] = $issuetype->getName();
			}

			$this->issuetypes = $return_array;
		}

		public function runListFieldvalues(TBGRequest $request)
		{
			$field_key = $request->getParameter('field_key');
			$return_array = array('description' => null, 'type' => null, 'choices' => null);
			if ($field_key == 'title' || in_array($field_key, TBGDatatypeBase::getAvailableFields(true)))
			{
				switch ($field_key)
				{
					case 'title':
						$return_array['description'] = TBGContext::getI18n()->__('Single line text input without formatting');
						$return_array['type'] = 'single_line_input';
						break;
					case 'description':
					case 'reproduction_steps':
						$return_array['description'] = TBGContext::getI18n()->__('Text input with wiki formatting capabilities');
						$return_array['type'] = 'wiki_input';
						break;
					case 'status':
					case 'resolution':
					case 'reproducability':
					case 'priority':
					case 'severity':
					case 'category':
						$return_array['description'] = TBGContext::getI18n()->__('Choose one of the available values');
						$return_array['type'] = 'choice';

						$classname = "TBG".ucfirst($field_key);
						$choices = $classname::getAll();
						foreach ($choices as $choice_key => $choice)
						{
							$return_array['choices'][$choice_key] = $choice->getName();
						}
						break;
					case 'percent_complete':
						$return_array['description'] = TBGContext::getI18n()->__('Value of percentage completed');
						$return_array['type'] = 'choice';
						$return_array['choices'][] = "1-100%";
						break;
					case 'owner':
					case 'assignee':
						$return_array['description'] = TBGContext::getI18n()->__('Select an existing user or <none>');
						$return_array['type'] = 'select_user';
						break;
					case 'estimated_time':
					case 'spent_time':
						$return_array['description'] = TBGContext::getI18n()->__('Enter time, such as points, hours, minutes, etc or <none>');
						$return_array['type'] = 'time';
						break;
					case 'milestone':
						$return_array['description'] = TBGContext::getI18n()->__('Select from available project milestones');
						$return_array['type'] = 'choice';
						if ($this->selected_project instanceof TBGProject)
						{
							$milestones = $this->selected_project->getAllMilestones();
							foreach ($milestones as $milestone)
							{
								$return_array['choices'][$milestone->getID()] = $milestone->getName();
							}
						}
						break;
				}
			}
			else
			{

			}

			$this->field_info = $return_array;
		}

		public function runGetBackdropPartial(TBGRequest $request)
		{
			try
			{
				$template_name = null;
				if ($request->hasParameter('issue_id'))
				{
					$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
					$options = array('issue' => $issue);
				}
				else
				{
					$options = array();
				}
				switch ($request->getParameter('key'))
				{
					case 'usercard':
						$template_name = 'main/usercard';
						if ($user_id = $request->getParameter('user_id'))
						{
							$user = TBGContext::factory()->TBGUser($user_id);
							$options['user'] = $user;
						}
						break;
					case 'login':
						$template_name = 'main/login';
						$options = $request->getParameters();
						$options['section'] = $request->getParameter('section', 'login');
						$options['mandatory'] = $request->getParameter('mandatory', false);
						break;
					case 'workflow_transition':
						$transition = TBGContext::factory()->TBGWorkflowTransition($request->getParameter('transition_id'));
						$template_name = $transition->getTemplate();
						$options['transition'] = $transition;
						break;
					case 'close_issue':
						$template_name = 'main/closeissue';
						break;
					case 'relate_issue':
						$template_name = 'main/relateissue';
						break;
					case 'markasduplicate_issue':
						$template_name = 'main/markasduplicate';
						break;
					case 'permissions':
						break;
					case 'project_config':
						$template_name = 'configuration/projectconfig_container';
						$project = TBGContext::factory()->TBGProject($request->getParameter('project_id'));
						$options['project'] = $project;
						$options['section'] = $request->getParameter('section', 'info');
						if ($request->hasParameter('edition_id'))
						{
							$edition = TBGContext::factory()->TBGEdition($request->getParameter('edition_id'));
							$options['edition'] = $edition;
							$options['selected_section'] = $request->getParameter('section', 'general');
						}
						break;
					case 'issue_add_item':
						$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
						$template_name = 'main/issueadditem';
						break;
					case 'client_users':
						$options['client'] = TBGContext::factory()->TBGClient($request->getParameter('client_id'));
						$template_name = 'main/clientusers';
						break;
					case 'dashboard_config':
						$template_name = 'main/dashboardconfig';
						$options['mandatory'] = true;
						break;
				}
				if ($template_name !== null)
				{
					return $this->renderJSON(array('content' => $this->getComponentHTML($template_name, $options)));
				}
			}
			catch (Exception $e)
			{
				$this->getResponse()->setHttpStatus(400);
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('An error occured: %error_message%', array('%error_message%' => $e->getMessage()))));
			}
			$this->getResponse()->setHttpStatus(400);
			return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Invalid template or parameter')));
		}

		public function runFindIssue(TBGRequest $request)
		{
			$status = 200;
			$message = null;
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					$status = 400;
					$message = TBGContext::getI18n()->__('Could not find this issue');
				}
			}
			else
			{
				$status = 400;
				$message = TBGContext::getI18n()->__('Please provide an issue number');
			}

			$searchfor = $request->getParameter('searchfor');

			if (strlen(trim($searchfor)) < 3 && !is_numeric($searchfor) && substr($searchfor, 0, 1) != '#')
			{
				$status = 400;
				$message = TBGContext::getI18n()->__('Please enter something to search for (3 characters or more) %searchfor%', array('searchfor' => $searchfor));
			}

			$this->getResponse()->setHttpStatus($status);
			if ($status == 400)
			{
				return $this->renderJSON(array('failed' => true, 'error' => $message));
			}
			
			list ($issues, $count) = TBGIssue::findIssuesByText($searchfor, $this->selected_project);
			return $this->renderJSON(array('failed' => false, 'content' => $this->getComponentHTML('main/find'.$request->getParameter('type').'issues', array('issue' => $issue, 'issues' => $issues, 'count' => $count))));
		}
		
		public function runFindDuplicateIssue(TBGRequest $request)
		{
			$status = 200;
			$message = null;
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					$status = 400;
					$message = TBGContext::getI18n()->__('Could not find this issue');
				}
			}
			else
			{
				$status = 400;
				$message = TBGContext::getI18n()->__('Please provide an issue number');
			}

			$searchfor = $request->getParameter('searchfor');

			if (strlen(trim($searchfor)) < 3 && !is_numeric($searchfor))
			{
				$status = 400;
				$message = TBGContext::getI18n()->__('Please enter something to search for (3 characters or more) %searchfor%', array('searchfor' => $searchfor));
			}

			$this->getResponse()->setHttpStatus($status);
			if ($status == 400)
			{
				return $this->renderJSON(array('failed' => true, 'error' => $message));
			}

			list ($issues, $count) = TBGIssue::findIssuesByText($searchfor, $this->selected_project);
			return $this->renderJSON(array('failed' => false, 'content' => $this->getComponentHTML('main/findduplicateissues', array('issue' => $issue, 'issues' => $issues, 'count' => $count))));
		}

		public function runRelateIssues(TBGRequest $request)
		{
			$status = 200;
			$message = null;
			if ($issue_id = $request->getParameter('issue_id'))
			{
				try
				{
					$issue = TBGContext::factory()->TBGIssue($issue_id);
				}
				catch (Exception $e)
				{
					$status = 400;
					$message = TBGContext::getI18n()->__('Could not find this issue');
				}
			}
			else
			{
				$status = 400;
				$message = TBGContext::getI18n()->__('Please provide an issue number');
			}
			
			$this->getResponse()->setHttpStatus($status);
			if ($status == 400)
			{
				return $this->renderJSON(array('failed' => true, 'error' => $message));
			}

			$related_issues = $request->getParameter('relate_issues', array());

			$cc = 0;
			$message = TBGContext::getI18n()->__('Unknown error');
			if (count($related_issues))
			{
				$mode = $request->getParameter('relate_action');
				$content = '';
				foreach ($related_issues as $issue_id)
				{
					try
					{
						$related_issue = TBGContext::factory()->TBGIssue((int) $issue_id);
						if ($mode == 'relate_children')
						{
							$issue->addChildIssue($related_issue);
						}
						else
						{
							$issue->addParentIssue($related_issue);
						}
						$cc++;
						$content .= $this->getTemplateHTML('main/relatedissue', array('related_issue' => $related_issue));
					}
					catch (Exception $e)
					{
						$this->getResponse()->setHttpStatus(400);
						return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('An error occured when relating issues: %error%', array('%error%' => $e->getMessage()))));
					}
				}
			}
			else
			{
				$message = TBGContext::getI18n()->__('Please select at least one issue');
			}

			if ($cc > 0)
			{
				return $this->renderJSON(array('failed' => false, 'content' => $content, 'message' => TBGContext::getI18n()->__('The related issue was added')));
			}
			else
			{
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('An error occured when relating issues: %error%', array('%error%' => $message))));
			}
		}

		public function runVoteForIssue(TBGRequest $request)
		{
			$i18n = TBGContext::getI18n();
			$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
			$vote_direction = $request->getParameter('vote');
			if ($issue instanceof TBGIssue && !$issue->hasUserVoted(TBGContext::getUser()->getID(), ($vote_direction == 'up')))
			{
				$issue->vote(($vote_direction == 'up'));
				return $this->renderJSON(array('content' => $issue->getVotes(), 'message' => $i18n->__('Vote added')));
			}

		}

		public function runToggleFriend(TBGRequest $request)
		{
			try
			{
				$friend_user = TBGContext::factory()->TBGUser($request->getParameter('user_id'));
				$mode = $request->getParameter('mode');
				if ($mode == 'add')
				{
					TBGContext::getUser()->addFriend($friend_user);
				}
				else
				{
					TBGContext::getUser()->removeFriend($friend_user);
				}
				return $this->renderJSON(array('failed' => false, 'mode' => $mode));
			}
			catch (Exception $e)
			{
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Could not add or remove friend')));
			}
		}
		
		public function runToggleAffectedConfirmed(TBGRequest $request)
		{
			TBGContext::loadLibrary('ui');
			try
			{
				$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
				
				if (!$issue->canEditIssue())
				{
					$this->getResponse()->setHttpStatus(400);
					return $this->renderJSON(array('failed' => true, 'error' => __('You are not allowed to do this')));
				}
				
				switch ($request->getParameter('affected_type'))
				{
					case 'edition':
						if (!$issue->getProject()->isEditionsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => __('Editions are disabled')));
						}
						
						$editions = $issue->getEditions();
						$edition = $editions[$request->getParameter('affected_id')];
						
						if ($edition['confirmed'] == true)
						{
							$issue->confirmAffectedEdition($edition['edition'], false);
							
							$message = TBGContext::getI18n()->__('Edition <b>%edition%</b> is now unconfirmed for this issue', array('%edition%' => $edition['edition']->getName()));
							$alt = TBGContext::getI18n()->__('No');
							$src = image_url('action_cancel_small.png');
						}
						else
						{
							$issue->confirmAffectedEdition($edition['edition']);
							
							$message = TBGContext::getI18n()->__('Edition <b>%edition%</b> is now confirmed for this issue', array('%edition%' => $edition['edition']->getName()));
							$alt = TBGContext::getI18n()->__('Yes');
							$src = image_url('action_ok_small.png');
						}
						
						break;
					case 'component':
						if (!$issue->getProject()->isComponentsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => __('Components are disabled')));
						}
						
						$components = $issue->getComponents();
						$component = $components[$request->getParameter('affected_id')];
						
						if ($component['confirmed'] == true)
						{
							$issue->confirmAffectedComponent($component['component'], false);
							
							$message = TBGContext::getI18n()->__('Component <b>%component%</b> is now unconfirmed for this issue', array('%component%' => $component['component']->getName()));
							$alt = TBGContext::getI18n()->__('No');
							$src = image_url('action_cancel_small.png');
						}
						else
						{
							$issue->confirmAffectedComponent($component['component']);
							
							$message = TBGContext::getI18n()->__('Component <b>%component%</b> is now confirmed for this issue', array('%component%' => $component['component']->getName()));
							$alt = TBGContext::getI18n()->__('Yes');
							$src = image_url('action_ok_small.png');
						}
						
						break;
					case 'build':
						if (!$issue->getProject()->isBuildsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => __('Releases are disabled')));
						}
						
						$builds = $issue->getBuilds();
						$build = $builds[$request->getParameter('affected_id')];
						
						if ($build['confirmed'] == true)
						{
							$issue->confirmAffectedBuild($build['build'], false);
							
							$message = TBGContext::getI18n()->__('Release <b>%build%</b> is now unconfirmed for this issue', array('%build%' => $build['build']->getName()));
							$alt = TBGContext::getI18n()->__('No');
							$src = image_url('action_cancel_small.png');
						}
						else
						{
							$issue->confirmAffectedBuild($build['build']);
							
							$message = TBGContext::getI18n()->__('Release <b>%build%</b> is now confirmed for this issue', array('%build%' => $build['build']->getName()));
							$alt = TBGContext::getI18n()->__('Yes');
							$src = image_url('action_ok_small.png');
						}
						
						break;
					default:
						throw new Exception('Internal error');
						break;
				}
				
				return $this->renderJSON(array('failed' => false, 'message' => $message, 'alt' => $alt, 'src' => $src));
			}
			catch (Exception $e)
			{
				$this->getResponse()->setHttpStatus(400);
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('An internal error has occured')));
			}
		}
		
		public function runRemoveAffected(TBGRequest $request)
		{
			TBGContext::loadLibrary('ui');
			try
			{
				$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
				
				if (!$issue->canEditIssue())
				{
					$this->getResponse()->setHttpStatus(400);
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You are not allowed to do this')));
				}
				
				switch ($request->getParameter('affected_type'))
				{
					case 'edition':
						if (!$issue->getProject()->isEditionsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Editions are disabled')));
						}
						
						$editions = $issue->getEditions();
						$edition = $editions[$request->getParameter('affected_id')];
						
						$issue->removeAffectedEdition($edition['edition']);
						
						$message = TBGContext::getI18n()->__('Edition <b>%edition%</b> is no longer affected by this issue', array('%edition%' => $edition['edition']->getName()));
												
						break;
					case 'component':
						if (!$issue->getProject()->isComponentsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Components are disabled')));
						}
						
						$components = $issue->getComponents();
						$component = $components[$request->getParameter('affected_id')];
						
						$issue->removeAffectedComponent($component['component']);
						
						$message = TBGContext::getI18n()->__('Component <b>%component%</b> is no longer affected by this issue', array('%component%' => $component['component']->getName()));
												
						break;
					case 'build':
						if (!$issue->getProject()->isBuildsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Releases are disabled')));
						}
						
						$builds = $issue->getBuilds();
						$build = $builds[$request->getParameter('affected_id')];
						
						$issue->removeAffectedBuild($build['build']);
						
						$message = TBGContext::getI18n()->__('Release <b>%build%</b> is no longer affected by this issue', array('%build%' => $build['build']->getName()));
											
						break;
					default:
						throw new Exception('Internal error');
						break;
				}
				
				$editions = array();
				$components = array();
				$builds = array();
				
				if($issue->getProject()->isEditionsEnabled())
				{
					$editions = $issue->getEditions();
				}
				
				if($issue->getProject()->isComponentsEnabled())
				{
					$components = $issue->getComponents();
				}

				if($issue->getProject()->isBuildsEnabled())
				{
					$builds = $issue->getBuilds();
				}
				
				$count = count($editions) + count($components) + count($builds) - 1;
				
				return $this->renderJSON(array('failed' => false, 'message' => $message, 'itemcount' => $count));
			}
			catch (Exception $e)
			{
				$this->getResponse()->setHttpStatus(400);
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('An internal error has occured')));
			}
		}
		
		public function runStatusAffected(TBGRequest $request)
		{
			TBGContext::loadLibrary('ui');
			try
			{
				$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
				$status = TBGContext::factory()->TBGStatus($request->getParameter('status_id'));
				if (!$issue->canEditIssue())
				{
					$this->getResponse()->setHttpStatus(400);
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You are not allowed to do this')));
				}
				
				switch ($request->getParameter('affected_type'))
				{
					case 'edition':
						if (!$issue->getProject()->isEditionsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Editions are disabled')));
						}
						$editions = $issue->getEditions();
						$edition = $editions[$request->getParameter('affected_id')];

						$issue->setAffectedEditionStatus($edition['edition'], $status);
						
						$message = TBGContext::getI18n()->__('Edition <b>%edition%</b> is now %status%', array('%edition%' => $edition['edition']->getName(), '%status%' => $status->getName()));
												
						break;
					case 'component':
						if (!$issue->getProject()->isComponentsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Components are disabled')));
						}
						$components = $issue->getComponents();
						$component = $components[$request->getParameter('affected_id')];
						
						$issue->setAffectedcomponentStatus($component['component'], $status);
						
						$message = TBGContext::getI18n()->__('Component <b>%component%</b> is now %status%', array('%component%' => $component['component']->getName(), '%status%' => $status->getName()));
												
						break;
					case 'build':
						if (!$issue->getProject()->isBuildsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Releases are disabled')));
						}
						$builds = $issue->getBuilds();
						$build = $builds[$request->getParameter('affected_id')];

						$issue->setAffectedbuildStatus($build['build'], $status);
						
						$message = TBGContext::getI18n()->__('Release <b>%build%</b> is now %status%', array('%build%' => $build['build']->getName(), '%status%' => $status->getName()));
												
						break;
					default:
						throw new Exception('Internal error');
						break;
				}
				
				return $this->renderJSON(array('failed' => false, 'message' => $message, 'colour' => $status->getColor(), 'name' => $status->getName()));
			}
			catch (Exception $e)
			{
				$this->getResponse()->setHttpStatus(400);
				return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('An internal error has occured')));
			}
		}	
			
		public function runAddAffected(TBGRequest $request)
		{
			TBGContext::loadLibrary('ui');
			try
			{
				$issue = TBGContext::factory()->TBGIssue($request->getParameter('issue_id'));
				$statuses = TBGStatus::getAll();

				if (!$issue->canEditIssue())
				{
					$this->getResponse()->setHttpStatus(400);
					return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('You are not allowed to do this')));
				}
				
				switch ($request->getParameter('item_type'))
				{
					case 'edition':
						if (!$issue->getProject()->isEditionsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Editions are disabled')));
						}
						
						$edition = TBGContext::factory()->TBGEdition($request->getParameter('which_item_edition'));
						
						if (TBGIssueAffectsEditionTable::getTable()->getByIssueIDandEditionID($issue->getID(), $edition->getID()))
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('%item% is already affected by this issue', array('%item%' => $edition->getName()))));
						}
						
						$edition = $issue->addAffectedEdition($edition);

						$item = $edition;
						$itemtype = 'edition';
						$itemtypename = TBGContext::getI18n()->__('Edition');
						$content = get_template_html('main/affecteditem', array('item' => $item, 'itemtype' => $itemtype, 'itemtypename' => $itemtypename, 'issue' => $issue, 'statuses' => $statuses));
						
						$message = TBGContext::getI18n()->__('Edition <b>%edition%</b> is now affected by this issue', array('%edition%' => $edition['edition']->getName()));
												
						break;
					case 'component':
						if (!$issue->getProject()->isComponentsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Components are disabled')));
						}
						
						$component = TBGContext::factory()->TBGComponent($request->getParameter('which_item_component'));
						
						if (TBGIssueAffectsComponentTable::getTable()->getByIssueIDandComponentID($issue->getID(), $component->getID()))
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('%item% is already affected by this issue', array('%item%' => $component->getName()))));
						}
						
						$component = $issue->addAffectedComponent($component);
						
						$item = $component;
						$itemtype = 'component';
						$itemtypename = TBGContext::getI18n()->__('Component');
						$content = get_template_html('main/affecteditem', array('item' => $item, 'itemtype' => $itemtype, 'itemtypename' => $itemtypename, 'issue' => $issue, 'statuses' => $statuses));
						
						$message = TBGContext::getI18n()->__('Component <b>%component%</b> is now affected by this issue', array('%component%' => $component['component']->getName()));
												
						break;
					case 'build':
						if (!$issue->getProject()->isBuildsEnabled())
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('Releases are disabled')));
						}
						
						$build = TBGContext::factory()->TBGBuild($request->getParameter('which_item_build'));

						
						if (TBGIssueAffectsBuildTable::getTable()->getByIssueIDandBuildID($issue->getID(), $build->getID()))
						{
							$this->getResponse()->setHttpStatus(400);
							return $this->renderJSON(array('failed' => true, 'error' => TBGContext::getI18n()->__('%item% is already affected by this issue', array('%item%' => $build->getName()))));
						}
												
						$build = $issue->addAffectedBuild($build);
						
						$item = $build;
						$itemtype = 'build';
						$itemtypename = TBGContext::getI18n()->__('Release');
						$content = get_template_html('main/affecteditem', array('item' => $item, 'itemtype' => $itemtype, 'itemtypename' => $itemtypename, 'issue' => $issue, 'statuses' => $statuses));
												
						$message = TBGContext::getI18n()->__('Release <b>%build%</b> is now affected by this issue', array('%build%' => $build['build']->getName()));
												
						break;
					default:
						throw new Exception('Internal error');
						break;
				}
				
				$editions = array();
				$components = array();
				$builds = array();
				
				if($issue->getProject()->isEditionsEnabled())
				{
					$editions = $issue->getEditions();
				}
				
				if($issue->getProject()->isComponentsEnabled())
				{
					$components = $issue->getComponents();
				}

				if($issue->getProject()->isBuildsEnabled())
				{
					$builds = $issue->getBuilds();
				}
				
				$count = count($editions) + count($components) + count($builds);
				
				return $this->renderJSON(array('failed' => false, 'content' => $content, 'message' => $message, 'itemcount' => $count));
			}
			catch (Exception $e)
			{
				$this->getResponse()->setHttpStatus(400);
				return $this->renderJSON(array('failed' => true, 'error' => $e->getMessage()));
			}
		}
		
		/**
		 * Reset user password
		 * 
		 * @param TBGRequest $request The request object
		 * 
		 */
		public function runReset(TBGRequest $request)
		{
			$i18n = TBGContext::getI18n();
			
			if ($request->hasParameter('user') && $request->hasParameter('id') && $request->hasParameter('forgot_password_mail'))
			{
				try
				{
					$user = TBGUser::getByUsername($request->getParameter('user'));
					if ($user instanceof TBGUser)
					{
						if ($request->getParameter('id') == $user->getHashPassword())
						{
							if ($request->getParameter('forgot_password_mail') == $user->getEmail())
							{
								$password = $user->createPassword();
								$user->changePassword($password);
								$user->save();
								TBGEvent::createNew('core', 'password_reset', $user, array('password' => $password))->trigger();
								return $this->renderJSON(array('message' => $i18n->__('A password has been autogenerated for you. To log in, use the following password:') . ' <b>' . $password . '</b>'));
							}
							else
							{
								throw new Exception('Invalid email address');
							}
							
						}
						else
						{
							throw new Exception('Invalid user');
						}
					}
					else
					{
						throw new Exception('User not specified');	
					}
				}
				catch (Exception $e)
				{
					return $this->renderJSON(array('failed' => true, 'error' => $i18n->__($e->getMessage())));
				}
			}
			else
			{
				return $this->renderJSON(array('failed' => true, 'error' => 'An internal error has occured'));
			}
		}
		
		/**
		 * Generate captcha picture
		 * 
		 * @param TBGRequest $request The request object
		 * @global array $_SESSION['activation_number'] The session captcha activation number
		 */			
		public function runCaptcha(TBGRequest $request)
		{
			TBGContext::loadLibrary('ui');
			
			if (!function_exists('imagecreatetruecolor'))
			{
				return $this->return404();
			}
			
			$this->getResponse()->setContentType('image/png');
			$this->getResponse()->setDecoration(TBGResponse::DECORATE_NONE);
			$chain = str_split($_SESSION['activation_number'],1);
			$size = getimagesize(image_url('numbers/0.png', false, 'core', false));
			$captcha = imagecreatetruecolor($size[0]*sizeof($chain), $size[1]);
			foreach ($chain as $n => $number)
			{
				$pic = imagecreatefrompng(image_url('numbers/' . $number . '.png', false, 'core', false));
				imagecopymerge($captcha, $pic, $size[0]*$n, 0, 0, 0, imagesx($pic), imagesy($pic), 100);
				imagedestroy($pic);
			}
			imagepng($captcha);
			imagedestroy($captcha);
			
			return true;
		}
		
		public function runIssueGetTempFieldValue(TBGRequest $request)
		{
			switch ($request->getParameter('field'))
			{
				case 'assigned_to':
					if ($request->getParameter('identifiable_type') == TBGIdentifiableClass::TYPE_USER)
					{
						$identifiable = TBGContext::factory()->TBGUser($request->getParameter('value'));
						$content = $this->getComponentHTML('main/userdropdown', array('user' => $identifiable));
					}
					elseif ($request->getParameter('identifiable_type') == TBGIdentifiableClass::TYPE_TEAM)
					{
						$identifiable = TBGContext::factory()->TBGTeam($request->getParameter('value'));
						$content = $this->getComponentHTML('main/teamdropdown', array('team' => $identifiable));
					}
					
					return $this->renderJSON(array('failed' => false, 'content' => $content));
					break;
			}
		}
		
}