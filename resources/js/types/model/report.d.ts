import { User } from "@/types/model/user"

export interface Report {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  reason: string
  description: string | null
  user_id: number
  reportable_type: string
  reportable_id: number
  // relations
  user: User
  reportable: Report
}
