import Head from "next/head";
import {useRouter} from "next/router";

const ExamView = () => {
  const router = useRouter();
  const externalId = router.query.id as string;

  return (
    <>
      <Head>
        <title>Examination - {externalId}</title>
      </Head>
      <div></div>
    </>
  )
}

export default ExamView;
