<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07/06/19
 * Time: 02:24 PM
 */
namespace OY\Customer\Controller\Adminhtml\Plan;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    protected $_planRepository;

    protected $_planFactory;

    public function __construct(
        Action\Context $context,
        \OY\Plan\Model\Repository\PlanRepository $planRepository,
        \OY\Plan\Model\PlanFactory $planFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
    )
    {
        parent::__construct($context); // TODO: Change the autogenerated stub
        $this->_planRepository = $planRepository;
        $this->_planFactory =$planFactory;
        $this->dateFilter = $dateFilter;
    }

    public function execute()
    {

        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        $id = $this->getRequest()->getParam('value_id');

        if ($data) {

            if($id){
                $model = $this->_planRepository->getById($data['value_id']);
            }else{
                $model = $this->_planFactory->create();
            }

            if (!$model->getId() && $data['value_id']!="") {
                $this->messageManager->addErrorMessage(__('This plan no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $strTo = strtotime($data['from']);
                $strTo =$this->dateFilter->filter($data['from']);

                $typePlan = 'Semanal';

                $count = 1;

                if($data['plan']=='Anual')
                    $typePlan = 'Anual';
                if($data['plan']=='Mensual')
                    $typePlan = 'Mensual';
                if($data['plan']=='Trimestral')
                    $typePlan = 'Trimestral';
                if($data['plan']=='Semestral')
                    $typePlan = 'Semestral';

                $plan = $typePlan;


                switch ($typePlan) {
                    case 'Anual':
                        //$dateTo = date("+".$data['plan_year']." years", $strTo);
                        $dateTo=date("Y-m-d H:i:s", strtotime("+1 years", strtotime($strTo)));
                        break;
                    case 'Mensual':
                        //$dateTo = date("+".$data['plan_month']." months", $strTo);
                        $dateTo=date("Y-m-d H:i:s", strtotime("+1 months", strtotime($strTo)));
                        break;
                    case 'Trimestral':
                        //$dateTo = date("+".$data['plan_month']." months", $strTo);
                        $dateTo=date("Y-m-d H:i:s", strtotime("+3 months", strtotime($strTo)));
                        break;
                    case 'Semestral':
                        //$dateTo = date("+".$data['plan_month']." months", $strTo);
                        $dateTo=date("Y-m-d H:i:s", strtotime("+6 months", strtotime($strTo)));
                        break;
                    default:
                        //$dateTo = date("+".$count." weeks", $strTo);
                        $weeks = $count*7;
                        $dateTo=date("Y-m-d H:i:s", strtotime("+".$weeks." days", strtotime($strTo)));
                }

                $now= new \DateTime();

                $model->setData('customer_id',$data['customer_id']);
                //$model->setData('plan',$plan);

                $model->setData('from',date("Y-m-d H:i:s", strtotime("+3 hours", strtotime($strTo))));
                //$model->setTo($this->dateFilter->filter(date('d/m/Y', $dateTo)));
                $strToTo = strtotime($data['to']);
                $strToTo =$this->dateFilter->filter($data['to']);

                $model->setData('to',date("Y-m-d H:i:s", strtotime("+3 hours", strtotime($strToTo))));

                $model->setData('access_number',0);
                if($data['access_number'])
                    $model->setData('access_number',$data['access_number']);

                $model->setData('access_enabled',0);
                if($data['access_enabled'])
                    $model->setData('access_enabled',$data['access_enabled']);

                $this->_planRepository->save($model);

                $this->messageManager->addSuccessMessage(__('The plan information has been saved.'));

                return $resultRedirect->setPath('customer/index/edit/id/'.$data['customer_id']);

            } catch (LocalizedException $e) {

                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {

                $this->messageManager->addException($e, __('Something went wrong while saving the reason information.'));
                $this->messageManager->addException($e, $e->getMessage());
            }

            return $resultRedirect->setPath('customer/index/edit',['id'=>$data['customer_id']]);

        }
        return $resultRedirect->setPath('customer/index/edit',['id'=>$data['customer_id']]);
    }


    protected function _isAllowed()
    {
        return true;
    }
}
