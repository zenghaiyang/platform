<?php

namespace Platform\enum;

final class JobStatus
{
    public const WORK = 1;

    public const RESIGN = 2;

    public const WORK_TEXT = '在职';

    public const RESIGN_TEXT = '离职';

    public $jobStatus;

    public $jobStatusText;

    /**
     * 设置属性
     * @param ?int $jobStatus 在职状态
     * @param ?string $jobStatusText 在职状态描述
     * @author lca
     * @date 2023/9/27 15:23
     */
    public function setProperties(int $jobStatus = null, string $jobStatusText = null): void
    {
        if ($jobStatus === self::WORK || $jobStatusText === self::WORK_TEXT) {
            $this->jobStatus = self::WORK;
            $this->jobStatusText = self::WORK_TEXT;
        } elseif ($jobStatus === self::RESIGN || $jobStatusText === self::RESIGN_TEXT) {
            $this->jobStatus = self::RESIGN;
            $this->jobStatusText = self::RESIGN_TEXT;
        }
    }

    /**
     * 转换性别
     * @return array|null
     * @author lca
     * @date 2023/9/26 10:14
     */
    public function getJobStatusArr(): ?array
    {
        if ($this->jobStatus === self::WORK || $this->jobStatusText === self::WORK_TEXT) {
            return ['job_status' => self::WORK, 'job_status_text' => self::WORK_TEXT];
        }

        if ($this->jobStatus === self::RESIGN || $this->jobStatusText === self::RESIGN_TEXT) {
            return ['job_status' => self::RESIGN, 'job_status_text' => self::RESIGN_TEXT];
        }

        return null;
    }
}
