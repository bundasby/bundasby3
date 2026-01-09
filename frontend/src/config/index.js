// Role Menu Configuration Index
// Export all role-based menu configurations

import adminMenus from './adminMenus'
import bundaMenus from './bundaMenus'
import juriMenus from './juriMenus'
import dinasMenus from './dinasMenus'
import mitraMenus from './mitraMenus'

// Role definitions - MUST match backend RoleSeeder.php codes
export const roles = {
  SUPER_ADMIN: 'super_admin',
  ADMIN: 'admin',
  BUNDA_KOTA: 'bunda_kota',
  BUNDA_KECAMATAN: 'bunda_kecamatan',
  BUNDA_KELURAHAN: 'bunda_kelurahan',
  GUGUS_TUGAS: 'gugus_tugas',
  MITRA_PAUD: 'mitra_paud',
  FORUM_KOMUNIKASI: 'forum_komunikasi',
  KELOMPOK_KERJA: 'kelompok_kerja',
  JURI_APRESIASI: 'juri_apresiasi',
  KEMENDIKDASMEN: 'kemendikdasmen',
  DINAS: 'dinas',
  // Legacy aliases for backward compatibility
  BUNDA_PAUD: 'bunda_paud',
  JURI: 'juri',
  MITRA: 'mitra'
}

// Role display names
export const roleNames = {
  [roles.SUPER_ADMIN]: 'Super Admin',
  [roles.ADMIN]: 'Admin',
  [roles.BUNDA_KOTA]: 'Bunda PAUD Kota',
  [roles.BUNDA_KECAMATAN]: 'Bunda PAUD Kecamatan',
  [roles.BUNDA_KELURAHAN]: 'Bunda PAUD Kelurahan',
  [roles.GUGUS_TUGAS]: 'Gugus Tugas PAUD HI',
  [roles.MITRA_PAUD]: 'Mitra PAUD',
  [roles.FORUM_KOMUNIKASI]: 'Forum Komunikasi PAUD',
  [roles.KELOMPOK_KERJA]: 'Kelompok Kerja',
  [roles.JURI_APRESIASI]: 'Juri Apresiasi',
  [roles.KEMENDIKDASMEN]: 'Kemendikdasmen',
  [roles.DINAS]: 'Dinas Pendidikan',
  // Legacy
  [roles.BUNDA_PAUD]: 'Bunda PAUD',
  [roles.JURI]: 'Juri',
  [roles.MITRA]: 'Mitra PAUD'
}

// Role colors for badges
export const roleColors = {
  [roles.SUPER_ADMIN]: 'bg-purple-500',
  [roles.ADMIN]: 'bg-blue-500',
  [roles.BUNDA_KOTA]: 'bg-pink-500',
  [roles.BUNDA_KECAMATAN]: 'bg-pink-400',
  [roles.BUNDA_KELURAHAN]: 'bg-pink-300',
  [roles.GUGUS_TUGAS]: 'bg-teal-500',
  [roles.MITRA_PAUD]: 'bg-orange-500',
  [roles.FORUM_KOMUNIKASI]: 'bg-indigo-500',
  [roles.KELOMPOK_KERJA]: 'bg-cyan-500',
  [roles.JURI_APRESIASI]: 'bg-yellow-500',
  [roles.KEMENDIKDASMEN]: 'bg-red-500',
  [roles.DINAS]: 'bg-green-500',
  // Legacy
  [roles.BUNDA_PAUD]: 'bg-pink-500',
  [roles.JURI]: 'bg-yellow-500',
  [roles.MITRA]: 'bg-orange-500'
}

// Role icons
export const roleIcons = {
  [roles.SUPER_ADMIN]: '👑',
  [roles.ADMIN]: '🛡️',
  [roles.BUNDA_KOTA]: '👩‍👧',
  [roles.BUNDA_KECAMATAN]: '👩',
  [roles.BUNDA_KELURAHAN]: '👧',
  [roles.GUGUS_TUGAS]: '👥',
  [roles.MITRA_PAUD]: '🤝',
  [roles.FORUM_KOMUNIKASI]: '💬',
  [roles.KELOMPOK_KERJA]: '👷',
  [roles.JURI_APRESIASI]: '⚖️',
  [roles.KEMENDIKDASMEN]: '🏛️',
  [roles.DINAS]: '🏢',
  // Legacy
  [roles.BUNDA_PAUD]: '👩‍👧',
  [roles.JURI]: '⚖️',
  [roles.MITRA]: '🤝'
}

// Get menu by role
export function getMenuByRole(role) {
  switch (role) {
    case roles.SUPER_ADMIN:
    case roles.ADMIN:
      return adminMenus
    case roles.BUNDA_KOTA:
    case roles.BUNDA_KECAMATAN:
    case roles.BUNDA_KELURAHAN:
    case roles.BUNDA_PAUD:
      return bundaMenus
    case roles.JURI_APRESIASI:
    case roles.JURI:
      return juriMenus
    case roles.DINAS:
    case roles.KEMENDIKDASMEN:
      return dinasMenus
    case roles.MITRA_PAUD:
    case roles.FORUM_KOMUNIKASI:
    case roles.KELOMPOK_KERJA:
    case roles.GUGUS_TUGAS:
    case roles.MITRA:
      return mitraMenus
    default:
      return adminMenus
  }
}

// Export all
export {
  adminMenus,
  bundaMenus,
  juriMenus,
  dinasMenus,
  mitraMenus
}

export default {
  roles,
  roleNames,
  roleColors,
  roleIcons,
  getMenuByRole,
  adminMenus,
  bundaMenus,
  juriMenus,
  dinasMenus,
  mitraMenus
}
