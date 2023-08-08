import Link from "next/link";

import { signOut } from "next-auth/react"
import {PropsWithChildren} from "react";

const LogOutButton = () => <button className="w-full" onClick={() => signOut()}>Sign out</button>

const NavElement = ({ children }: PropsWithChildren) => (
  <li className="hover:bg-indigo-300 rounded">
    {children}
  </li>
)

const NavBar = () => {
  return (
    <ul className="flex sm:flex-col overflow-hidden content-center justify-between">
      <NavElement>
        <Link className="truncate block w-full py-2" href="/app">
          <img src="//cdn.jsdelivr.net/npm/heroicons@1.0.1/outline/home.svg" className="w-7 sm:mx-2 mx-4 inline"/>
          <span className="hidden sm:inline">Home</span>
        </Link>
      </NavElement>
      <NavElement>
        <Link className="truncate block w-full py-2" href="/app/exams">
          <img src="//cdn.jsdelivr.net/npm/heroicons@1.0.1/outline/chart-bar.svg" className="w-7 sm:mx-2 mx-4 inline"/>
          <span className="hidden sm:inline">Exams</span>
        </Link>
      </NavElement>
      <NavElement>
        <LogOutButton />
      </NavElement>
    </ul>
  )
}

export default NavBar
