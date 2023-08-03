import {fetch, FetchResponse} from "../../../utils/dataAccess";
import {ExamModel} from "../../../types/ExamModel";
import {useSession} from "next-auth/react";
import Link from "next/link";

const ExamList = ({ exams }: { exams: ExamModel[] }) => {

  return (
    <table className="table-auto">
      <thead>
      <tr>
        <th>Id</th>
        <th>Examinated at</th>
      </tr>
      </thead>
      <tbody>
      {exams.map(exam => (
        <tr key={exam.externalId}>
          <td className="border-2">
            <Link href={`/app/exams/${exam.externalId}`}>{exam.externalId}</Link>
          </td>
          <td className="border-2">{exam.examinedAt}</td>
        </tr>
      ))}
      </tbody>
    </table>
  )
}

export default ExamList;
