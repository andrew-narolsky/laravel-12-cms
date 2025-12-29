export default class JobWatcher {

    static watch(job, progressBar, url, onFinish, onError = null) {
        let jobStatusChecker = null;

        progressBar.classList.remove('error', 'success');
        progressBar.style.width = 0;

        const intervalId = setInterval(async () => {
            if (jobStatusChecker !== null) return;
            jobStatusChecker = true;

            try {
                const { data: answer } = await axios.post(url, { job });

                progressBar.classList.remove('error', 'success');
                progressBar.style.width = answer.progress_now + '%';

                switch (answer.status) {
                    case 'failed':
                    case 'retrying':
                        progressBar.classList.add('error');
                        clearInterval(intervalId);

                        if (onError) onError(answer);
                        break;

                    case 'finished':
                        progressBar.classList.add('success');
                        progressBar.style.width = '100%';

                        onFinish(answer);
                        clearInterval(intervalId);
                        break;
                }

            } catch (error) {

            } finally {
                jobStatusChecker = null;
            }

        }, 1000);
    }
}
