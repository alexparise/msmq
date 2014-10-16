<?php

namespace Aztech\Rpc;

/*
 *
* (c) Copyright 1993 OPEN SOFTWARE FOUNDATION, INC.
* (c) Copyright 1993 HEWLETT-PACKARD COMPANY
* (c) Copyright 1993 DIGITAL EQUIPMENT CORPORATION
* To anyone who acknowledges that this file is provided "AS IS"
* without any express or implied warranty:
*                 permission to use, copy, modify, and distribute this
* file for any purpose is hereby granted without fee, provided that
* the above copyright notices and this notice appears in all source
* code copies, and that none of the names of Open Software
* Foundation, Inc., Hewlett-Packard Company, or Digital Equipment
* Corporation be used in advertising or publicity pertaining to
* distribution of the software without specific, written prior
* permission.  Neither Open Software Foundation, Inc., Hewlett-
* Packard Company, nor Digital Equipment Corporation makes any
* representations about the suitability of this software for any
* purpose.
*
*/
/*
 */
/*
 **
**  NAME:
**
**      ncastat.idl
**
**  FACILITY:
**
**      Network Computing Architecture (NCA)
**
**  ABSTRACT:
**
**  NCA-defined status codes.
**
**
*/

final class NcaStatus
{
    const nca_s_comm_failure              = 0x1C010001;
    const nca_s_op_rng_error              = 0x1C010002;
    const nca_s_unk_if                    = 0x1C010003;
    const nca_s_wrong_boot_time           = 0x1C010006;
    const nca_s_you_crashed               = 0x1C010009;
    const nca_s_proto_error               = 0x1C01000B;
    const nca_s_out_args_too_big          = 0x1C010013;
    const nca_s_server_too_busy           = 0x1C010014;
    const nca_s_fault_string_too_long     = 0x1C010015;
    const nca_s_unsupported_type          = 0x1C010017;
    const nca_s_fault_int_div_by_zero     = 0x1C000001;
    const nca_s_fault_addr_error          = 0x1C000002;
    const nca_s_fault_fp_div_zero         = 0x1C000003;
    const nca_s_fault_fp_underflow        = 0x1C000004;
    const nca_s_fault_fp_overflow         = 0x1C000005;
    const nca_s_fault_invalid_tag         = 0x1C000006;
    const nca_s_fault_invalid_bound       = 0x1C000007;
    const nca_s_rpc_version_mismatch      = 0x1C000008;
    const nca_s_unspec_reject             = 0x1C000009;
    const nca_s_bad_actid                 = 0x1C00000A;
    const nca_s_who_are_you_failed        = 0x1C00000B;
    const nca_s_manager_not_entered       = 0x1C00000C;
    const nca_s_fault_cancel              = 0x1C00000D;
    const nca_s_fault_ill_inst            = 0x1C00000E;
    const nca_s_fault_fp_error            = 0x1C00000F;
    const nca_s_fault_int_overflow        = 0x1C000010;
    const nca_s_fault_unspec              = 0x1C000012;
    const nca_s_fault_remote_comm_failure = 0x1C000013;
    const nca_s_fault_pipe_empty          = 0x1C000014;
    const nca_s_fault_pipe_closed         = 0x1C000015;
    const nca_s_fault_pipe_order          = 0x1C000016;
    const nca_s_fault_pipe_discipline     = 0x1C000017;
    const nca_s_fault_pipe_comm_error     = 0x1C000018;
    const nca_s_fault_pipe_memory         = 0x1C000019;
    const nca_s_fault_context_mismatch    = 0x1C00001A;
    const nca_s_fault_remote_no_memory    = 0x1C00001B;
    const nca_s_invalid_pres_context_id   = 0x1C00001C;
    const nca_s_unsupported_authn_level   = 0x1C00001D;
    const nca_s_invalid_checksum          = 0x1C00001F;
    const nca_s_invalid_crc               = 0x1C000020;
    const nca_s_fault_user_defined        = 0x1C000021;
    const nca_s_fault_tx_open_failed      = 0x1C000022;
    const nca_s_fault_codeset_conv_error  = 0x1C000023;
    const nca_s_fault_access_denied       = 0x00000005;
    const ncs_s_fault_ndr                 = 0x000006f7;
    const nca_s_fault_cant_perform        = 0x000006d8;

    public static function getErrorName($code)
    {
        static $constants = null;

        if ($constants === null) {
            $reflClass = new \ReflectionClass(self::class);
            $constants = $reflClass->getConstants();

            $constants = array_flip($constants);
        }

        return array_key_exists($code, $constants) ? $constants[$code] : false;
    }
}
