import Head from "next/head";
import {useRouter} from "next/router";
import {GetServerSideProps, InferGetServerSidePropsType} from "next";
import {withApiToken} from "../../../utils/auth";
import {fetch} from "../../../utils/dataAccess";

export const getServerSideProps: GetServerSideProps<{ clientToken: string }> = withApiToken(async ({ apiToken, context, }) => {
  const externalId = context.params!.id as string;
  const response = await fetch(`/api/exam_models/${externalId}/token`, {
    headers: {
      "Authorization": `Bearer ${apiToken}`
    }
  });

  return { props: {}}
})

const ExamView = ({ clientToken }: InferGetServerSidePropsType<typeof getServerSideProps>) => {
  const router = useRouter();
  const externalId = router.query.id as string;

  return (
    <>
      <Head>
        <title>Examination - {externalId}</title>
      </Head>
      {/*<Examination clientToken={clientToken} />*/}
    </>
  )
}

export default ExamView;
