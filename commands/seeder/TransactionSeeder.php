<?php

namespace app\commands\seeder;

use app\commands\models\Transaction;
use app\helpers\App;
use app\models\Member;
use app\widgets\CertificateOfIndigency;
use app\widgets\FinancialCertification;
use app\widgets\SocialCaseStudyReport;
use app\widgets\CertificateOfMarriageCounseling;
use app\widgets\CertificateOfCompliance;
use app\widgets\CertificateOfApparentDisability;
use yii\db\Expression;

class TransactionSeeder extends Seeder
{
	public $modelClass = 'app\commands\models\Transaction';
	public $member;

	public function __construct()
	{
		parent::__construct();
		// $this->member = array_keys(Member::dropdown('id', 'id'));
	}

	public function attributes($key)
	{
		$transaction_type = $this->randParams('transaction_types');

		$emergency_welfare_program = 0;


		if ($transaction_type == Transaction::EMERGENCY_WELFARE_PROGRAM) {
			$amount = rand(500, 1000);
			$emergency_welfare_program = $this->randParams('emergency_welfare_programs');
		}
		elseif ($transaction_type == Transaction::SOCIAL_PENSION) {
			$amount = 500;
		}
		elseif ($transaction_type == Transaction::DEATH_ASSISTANCE) {
			$amount = 5000;
		}
		else {
			$amount = 0;
		}

		$member = Member::findOne(rand(1, 31345));

		if ($transaction_type == Transaction::CERTIFICATE_OF_INDIGENCY) {
			$content = CertificateOfIndigency::widget([
                'model' => $member,
                'contentOnly' => true
            ]);
            $status = Transaction::CERTIFICATE_CREATED;
		}
		elseif ($transaction_type == Transaction::FINANCIAL_CERTIFICATION) {
			$content = FinancialCertification::widget([
                'model' => $member,
                'contentOnly' => true
            ]);
            $status = Transaction::CERTIFICATE_CREATED;
		}
		elseif ($transaction_type == Transaction::SOCIAL_CASE_STUDY_REPORT) {
			$content = SocialCaseStudyReport::widget([
                'model' => $member,
                'contentOnly' => true
            ]);
            $status = Transaction::CERTIFICATE_CREATED;
		}
		elseif ($transaction_type == Transaction::CERTIFICATE_OF_MARRIAGE_COUNSELING) {
			$content = CertificateOfMarriageCounseling::widget([
                'model' => $member,
                'contentOnly' => true
            ]);
            $status = Transaction::CERTIFICATE_CREATED;
		}
		elseif ($transaction_type == Transaction::CERTIFICATE_OF_COMPLIANCE) {
			$content = CertificateOfCompliance::widget([
                'model' => $member,
                'contentOnly' => true
            ]);
            $status = Transaction::CERTIFICATE_CREATED;
		}
		elseif ($transaction_type == Transaction::CERTIFICATE_OF_APPARENT_DISABILITY) {
			$content = CertificateOfApparentDisability::widget([
                'model' => $member,
                'contentOnly' => true
            ]);
            $status = Transaction::CERTIFICATE_CREATED;
		}
		elseif ($transaction_type == Transaction::EMERGENCY_WELFARE_PROGRAM) {
			$content = '';

			if (in_array($emergency_welfare_program, [1,2,3])) {
				$status = $this->rand([
	            	Transaction::NEW_TRANSACTION,
				    Transaction::MHO_APPROVED,
				    Transaction::MHO_DECLINED,
				    Transaction::MSWDO_HEAD_APPROVED,
				    Transaction::MSWDO_HEAD_DECLINED,
				    Transaction::MAYOR_APPROVED,
				    Transaction::MAYOR_DECLINED,
				    Transaction::BUDGET_OFFICER_CERTIFIED,
				    Transaction::DISBURSED,
				    Transaction::COMPLETED,
				    Transaction::MSWDO_CLERK_APPROVED,
				    Transaction::ACCOUNTING_COMPLETED,
				    Transaction::MHO_PROCESSING,
				    Transaction::MSWDO_CLERK_PROCESSING,
				    Transaction::MSWDO_HEAD_PROCESSING,
				    Transaction::MAYOR_PROCESSING,
				    Transaction::BUDGET_OFFICER_PROCESSING,
				    Transaction::ACCOUNTING_OFFICER_PROCESSING,
				    Transaction::DISBURSING_OFFICER_PROCESSING,
				    Transaction::ACCOUNTING_OFFICER_PROOFING,
	            ]);
			}
			else {
				$status = $this->rand([
	            	Transaction::NEW_TRANSACTION,
				    Transaction::MHO_APPROVED,
				    Transaction::MHO_DECLINED,
				    Transaction::MSWDO_HEAD_APPROVED,
				    Transaction::MSWDO_HEAD_DECLINED,
				    Transaction::MAYOR_APPROVED,
				    Transaction::MAYOR_DECLINED,
				    Transaction::BUDGET_OFFICER_CERTIFIED,
				    Transaction::DISBURSED,
				    Transaction::COMPLETED,
				    Transaction::MSWDO_CLERK_APPROVED,
				    Transaction::ACCOUNTING_COMPLETED,
				    Transaction::MHO_PROCESSING,
				    Transaction::MSWDO_CLERK_PROCESSING,
				    Transaction::MSWDO_HEAD_PROCESSING,
				    Transaction::MAYOR_PROCESSING,
				    Transaction::BUDGET_OFFICER_PROCESSING,
				    Transaction::ACCOUNTING_OFFICER_PROCESSING,
				    Transaction::DISBURSING_OFFICER_PROCESSING,
				    Transaction::ACCOUNTING_OFFICER_PROOFING,
	            ]);
			}
		}
		elseif ($transaction_type == Transaction::SENIOR_CITIZEN_ID_APPLICATION) {
			$content = '';
			$status = $this->rand([
            	Transaction::NEW_TRANSACTION,
			    Transaction::COMPLETED,
			    Transaction::MSWDO_CLERK_APPROVED,
			    Transaction::MSWDO_CLERK_PROCESSING,
			    Transaction::TREASURER_PROCESSING,
			    Transaction::PAYMENT_COMPLETED,
			    Transaction::ID_RELEASED,
            ]);
		}
		elseif ($transaction_type == Transaction::SOCIAL_PENSION) {
			$content = '';
			$status = $this->rand([
            	Transaction::NEW_TRANSACTION,
			    Transaction::MHO_APPROVED,
			    Transaction::MHO_DECLINED,
			    Transaction::MSWDO_HEAD_APPROVED,
			    Transaction::MSWDO_HEAD_DECLINED,
            ]);
		}
		elseif ($transaction_type == Transaction::DEATH_ASSISTANCE) {
			$content = '';
			$status = $this->rand([
            	Transaction::NEW_TRANSACTION,
			    Transaction::MHO_APPROVED,
			    Transaction::MHO_DECLINED,
			    Transaction::MSWDO_HEAD_APPROVED,
			    Transaction::MSWDO_HEAD_DECLINED,
			    Transaction::MAYOR_APPROVED,
			    Transaction::MAYOR_DECLINED,
			    Transaction::BUDGET_OFFICER_CERTIFIED,
			    Transaction::DISBURSED,
			    Transaction::COMPLETED,
			    Transaction::WHITE_CARD_CREATED,
			    Transaction::MSWDO_CLERK_APPROVED,
			    Transaction::ACCOUNTING_COMPLETED,
			    Transaction::MHO_PROCESSING,
			    Transaction::MSWDO_CLERK_PROCESSING,
			    Transaction::MSWDO_HEAD_PROCESSING,
			    Transaction::MAYOR_PROCESSING,
			    Transaction::BUDGET_OFFICER_PROCESSING,
			    Transaction::ACCOUNTING_OFFICER_PROCESSING,
			    Transaction::DISBURSING_OFFICER_PROCESSING,
			    Transaction::ACCOUNTING_OFFICER_PROOFING,
            ]);
		}
		else {
			$content = '';
            $status = $this->rand([
            	Transaction::NEW_TRANSACTION,
			    Transaction::MHO_APPROVED,
			    Transaction::MHO_DECLINED,
			    Transaction::MSWDO_HEAD_APPROVED,
			    Transaction::MSWDO_HEAD_DECLINED,
			    Transaction::MAYOR_APPROVED,
			    Transaction::MAYOR_DECLINED,
			    Transaction::BUDGET_OFFICER_CERTIFIED,
			    Transaction::DISBURSED,
			    Transaction::COMPLETED,
			    Transaction::WHITE_CARD_CREATED,
			    // Transaction::CERTIFICATE_CREATED,
			    Transaction::MSWDO_CLERK_APPROVED,
			    Transaction::ACCOUNTING_COMPLETED,
			    Transaction::MHO_PROCESSING,
			    Transaction::MSWDO_CLERK_PROCESSING,
			    Transaction::MSWDO_HEAD_PROCESSING,
			    Transaction::MAYOR_PROCESSING,
			    Transaction::BUDGET_OFFICER_PROCESSING,
			    Transaction::ACCOUNTING_OFFICER_PROCESSING,
			    Transaction::DISBURSING_OFFICER_PROCESSING,
			    Transaction::ACCOUNTING_OFFICER_PROOFING,
			    Transaction::TREASURER_PROCESSING,
			    Transaction::PAYMENT_COMPLETED,
			    Transaction::ID_RELEASED,
            ]);
		}

		$m = rand(1, 12);
		$d = rand(1, 28);

		$m = ($m <= 9)? "0{$m}": $m;
		$d = ($d <= 9)? "0{$d}": $d;

        $created_at = implode(' ', [date("2022-{$m}-{$d}"), $this->faker->time]);

		return [
            'member_id' => $member->id,
            'transaction_type' => $transaction_type,
            'emergency_welfare_program' => $emergency_welfare_program,
            'amount' => $amount,
            'status' => $status,
            'content' => 'content',
            // 'content' => $content,
            'remarks' => 'test',
            'files' => json_encode([]),
            'white_card' => '',
            'general_intake_sheet' => '',
            'obligation_request' => '',
            'petty_cash_voucher' => '',
            'token' => implode('', [time(), $key]),
            'senior_citizen_intake_sheet' => '',
            'social_pension_application_form' => '',
            'record_status' => Transaction::RECORD_ACTIVE,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $created_at,
            'updated_at' => $created_at,
		];
	}

	public function randParams($param)
	{
		return $this->faker->randomElement(array_keys(App::keyMapParams($param)));
	}

	public function rand($arr)
	{
		return $this->faker->randomElement($arr);
	}
}