<?php

namespace App\Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Actions\ProcessRealtimePunchAction;
use App\Modules\Attendance\DTOs\ZkTecoPunchData;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

#[Group('Attendance Module')]
class ZkTecoAdmsController extends Controller
{
    /**
     * ZKTeco machines send device info on connection.
     * We just need to respond with OK to keep the heartbeat alive.
     */
    public function handshake(Request $request)
    {
        return response('OK', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * ZKTeco machines POST actual punches here
     */
    public function receiveCdata(Request $request, ProcessRealtimePunchAction $processAction)
    {
        $sn = $request->query('SN', 'UNKNOWN');

        $body = $request->getContent();

        $lines = explode("\n", trim($body));
        $count = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $parts = explode("\t", $line);
            if (count($parts) >= 2) {

                $dto = ZkTecoPunchData::from([
                    'terminal_sn' => $sn,
                    'employee_pin' => $parts[0],
                    'punch_time' => $parts[1], // Spatie automatically casts the string to a Carbon object!
                    'punch_state' => $parts[2] ?? null,
                    'verify_type' => $parts[3] ?? null,
                ]);

                $processAction->execute($dto);
                $count++;
            }
        }

        Log::info("Processed {$count} punches from terminal {$sn}");

        return response('OK', 200)->header('Content-Type', 'text/plain');
    }
}
