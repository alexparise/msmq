<?php

namespace Aztech\Msmq;

/**
 * List of defined opnums in [MS-MQMP] – v20080207
 *
 * @author thibaud
 *
 */
final class Opnum
{
    const R_QMGetRemoteQueueName = 1;

    const R_QMOpenRemoteQueue = 2;

    const R_QMCloseRemoteQueueContext = 3;

    const R_QMCreateRemoteCursor = 4;

    const R_QMCreateObjectInternal = 6;

    const R_QMSetObjectSecurityInternal = 7;

    const R_QMGetObjectSecurityInternal = 8;

    const R_QMDeleteObject = 9;

    const R_QMGetObjectProperties = 10;

    const R_QMSetObjectProperties = 11;

    const R_QMObjectPathToObjectFormat = 12;

    const R_QMGetTmWhereabouts = 14;

    const R_QMEnlistTransaction = 15;

    const R_QMEnlistInternalTransaction = 16;

    const R_QMCommitTransaction = 17;

    const R_QMAbortTransaction = 18;

    const rpc_QMOpenQueueInternal = 19;

    const rpc_ACCloseHandle = 20;

    const rpc_ACCloseCursor = 22;

    const rpc_ACSetCursorProperties = 23;

    const rpc_ACHandleToFormatName = 26;

    const rpc_ACPurgeQueue = 27;

    const R_QMQueryQMRegistryInternal = 28;

    const R_QMGetRTQMServerPort = 31;
}
